<div
    style="border-bottom: 1px solid #585555"
    class="mb-3 pb-3"
>
    <div class="row">
        <div class="col-12">
            <h1 class="text-center mb-2">
                User Summary Report
            </h1>
        </div>
    </div>
</div>

@foreach ($data as $user)
    <div class="my-3">
        <x-base.card :title="$user['full_name']">
            <table class="w-full table-fixed border-collapse">
                <tbody>
                    <tr class="border-b">
                        <th class="text-end p-2 max-w-1/2 w-1/3">
                            User ID:
                        </th>
                        <td class="p-2">
                            {{ $user['user_id'] }}
                        </td>
                    </tr>
                    <tr class="border-b">
                        <th class="text-end p-2 max-w-1/2 w-1/3">
                            Username:
                        </th>
                        <td class="p-2">
                            {{ $user['username'] }}
                        </td>
                    </tr>
                    <tr class="border-b">
                        <th class="text-end p-2 max-w-1/2 w-1/3">
                            Email:
                        </th>
                        <td class="p-2">
                            {{ $user['email'] }}
                        </td>
                    </tr>
                    <tr class="border-b">
                        <th class="text-end p-2 max-w-1/2 w-1/3">
                            User Role:
                        </th>
                        <td class="p-2">
                            {{ $user['role'] }}
                        </td>
                    </tr>
                    <tr class="border-b">
                        <th class="text-end p-2 max-w-1/2 w-1/3">
                            User Created:
                        </th>
                        <td class="p-2">
                            {{ \Carbon\Carbon::parse($user['created_at'])->format('M d, Y') }}
                        </td>
                    </tr>
                    <tr class="border-b">
                        <th class="text-end p-2 max-w-1/2 w-1/3">
                            Profile Last Updated:
                        </th>
                        <td class="p-2">
                            {{ \Carbon\Carbon::parse($user['updated_at'])->format('M d, Y') }}
                        </td>
                    </tr>
                    @if (config('auth.password.settings.expire' !== 0))
                        <tr class="border-b">
                            <th class="text-end p-2 max-w-1/2 w-1/3">
                                Password Expires:
                            </th>
                            <td class="p-2">
                                {{ \Carbon\Carbon::parse($user['password_expires'])->format('M d, Y') }}
                            </td>
                        </tr>
                    @endif
                    @if ($user['deleted_at'])
                        <tr class="border-b">
                            <th class="text-end p-2 max-w-1/2 w-1/3">
                                Disabled On:
                            </th>
                            <td class="p-2">
                                {{ \Carbon\Carbon::parse($user['deleted_at'])->format('M d, Y') }}
                            </td>
                        </tr>
                    @endif
                    <tr class="border-b">
                        <th class="text-end p-2 max-w-1/2 w-1/3">
                            Last Login:
                        </th>
                        <td class="p-2">
                            @if ($user['last_login'] === 'Never')
                                Never
                            @else
                                {{ \Carbon\Carbon::parse($user['last_login'])->format('M d, Y') }}
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </x-base.card>
    </div>
@endForEach
