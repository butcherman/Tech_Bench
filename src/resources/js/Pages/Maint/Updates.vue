<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import BaseBadge from "@/Components/_Base/Badges/BaseBadge.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import { router } from "@inertiajs/vue3";

defineProps<{
    release: {
        current_version: string;
        latest_version: string | null;
        update_available: boolean;
        published_at: string | null;
        release_url: string | null;
        release_notes: string;
        check_status: "success" | "stale" | "unavailable";
        error: string | null;
    };
}>();

function formatDate(date: string | null): string {
    if (!date) return "Unknown";

    return new Intl.DateTimeFormat(undefined, {
        dateStyle: "long",
        timeStyle: "short",
    }).format(new Date(date));
}

function checkForUpdates(): void {
    console.log("reloading");
    router.reload({
        only: ["release"],
    });
}
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="flex justify-center">
        <Card class="tb-card">
            <div class="flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <h5>Installed Version</h5>
                    <BaseBadge
                        :text="release.current_version"
                        icon="check-circle"
                    />
                    <BaseButton
                        text="Check for Updates"
                        @click="checkForUpdates"
                    />
                </div>

                <div
                    v-if="release.check_status === 'stale'"
                    class="rounded-lg border border-amber-300 bg-amber-50 p-3 text-amber-800"
                >
                    <strong>Using Cached Release Information</strong>
                    <p>{{ release.error }}</p>
                </div>

                <div
                    v-else-if="release.check_status === 'unavailable'"
                    class="rounded-lg border border-red-300 bg-red-50 p-3 text-red-800"
                >
                    <strong>Unable to Check for Updates</strong>
                    <p>{{ release.error }}</p>
                </div>

                <div class="flex items-center justify-between">
                    <h5>Latest Stable Release</h5>
                    <BaseBadge
                        :text="release.latest_version"
                        :icon="
                            release.update_available
                                ? 'download'
                                : 'check-circle'
                        "
                    />
                </div>

                <div
                    v-if="release.latest_version"
                    class="border-t border-gray-200 pt-4"
                >
                    <div v-if="release.update_available">
                        <BaseBadge
                            text="Update Available"
                            icon="alert-circle"
                            class="mb-2"
                        />
                        <p>A newer version of Tech Bench is available.</p>
                    </div>

                    <div v-else>
                        <BaseBadge
                            text="Up to Date"
                            icon="check-circle"
                            class="mb-2"
                        />
                        <p>You are running the latest stable version.</p>
                    </div>
                </div>
            </div>
        </Card>
    </div>

    <div class="flex justify-center">
        <Card class="tb-card">
            <div class="flex flex-col gap-3">
                <h5>Release Information</h5>

                <p>
                    <strong>Published:</strong>
                    {{ formatDate(release.published_at) }}
                </p>

                <div class="border-t border-gray-200 pt-3">
                    <h5 class="mb-2">Release Notes</h5>
                    <div class="whitespace-pre-wrap text-sm">
                        {{
                            release.release_notes ||
                            "No release notes provided."
                        }}
                    </div>
                </div>

                <div v-if="release.release_url" class="flex justify-end">
                    <BaseButton
                        :href="release.release_url"
                        text="View GitHub Release"
                        icon="external-link"
                        target="_blank"
                    />
                </div>
            </div>
        </Card>
    </div>
</template>
