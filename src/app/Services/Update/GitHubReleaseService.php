<?php

namespace App\Services\Update;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
// use Illuminate\Http\Client\ConnectionException;
// use Illuminate\Http\Client\RequestException;
use RuntimeException;

class GitHubReleaseService
{
    private const RELEASE_URL =
        'https://api.github.com/repos/butcherman/Tech_Bench/releases/latest';

    private const CACHE_KEY = 'tech_bench.github.latest_release';

    public function check(): array
    {
        Artisan::call('version', [
            '--format' => 'compact',
        ]);

        $currentVersion = trim(
            preg_replace('/^Tech Bench\s+/', '', Artisan::output())
        );

        if ($currentVersion === '') {
            throw new RuntimeException(
                'The installed Tech Bench version is empty.'
            );
        }

        try {
            $release = Http::acceptJson()
                ->withHeaders([
                    'X-GitHub-Api-Version' => '2022-11-28',
                ])
                ->timeout(10)
                ->get(self::RELEASE_URL)
                ->throw()
                ->json();

            if (! is_array($release) || empty($release['tag_name'])) {
                throw new RuntimeException(
                    'GitHub returned invalid release information.'
                );
            }

            $latestVersion = ltrim($release['tag_name'], 'v');

            // Save the last successful response.
            Cache::forever(self::CACHE_KEY, [
                'latest_version' => $latestVersion,
                'published_at' => $release['published_at'] ?? null,
                'release_url' => $release['html_url'] ?? null,
                'release_notes' => $release['body'] ?? '',
            ]);

            return $this->formatRelease(
                $currentVersion,
                Cache::get(self::CACHE_KEY),
                'success'
            );

        } catch (ConnectionException|RequestException|RuntimeException $e) {
            $cachedRelease = Cache::get(self::CACHE_KEY);

            if ($cachedRelease) {
                return $this->formatRelease(
                    $currentVersion,
                    $cachedRelease,
                    'stale',
                    'GitHub could not be reached. Showing the last successful release information.'
                );
            }

            return [
                'current_version' => $currentVersion,
                'latest_version' => null,
                'update_available' => false,
                'published_at' => null,
                'release_url' => null,
                'release_notes' => '',
                'check_status' => 'unavailable',
                'error' => 'Unable to retrieve release information from GitHub.',
            ];
        }
    }

    private function formatRelease(
        string $currentVersion,
        array $release,
        string $status,
        ?string $error = null
    ): array {
        $latestVersion = $release['latest_version'];

        return [
            'current_version' => $currentVersion,
            'latest_version' => $latestVersion,
            'update_available' => version_compare(
                $latestVersion,
                $currentVersion,
                '>'
            ),
            'published_at' => $release['published_at'],
            'release_url' => $release['release_url'],
            'release_notes' => $release['release_notes'],
            'check_status' => $status,
            'error' => $error,
        ];
    }
}
