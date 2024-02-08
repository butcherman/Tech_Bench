<template>
    <div class="table-responsive">
        <table class="table">
            <tbody>
                <tr>
                    <th colspan="2">Two Factor Authentication</th>
                </tr>
                <tr>
                    <th class="text-end">Require Two-Factor Authentication</th>
                    <td>
                        With this enabled, users will have to verify their
                        identity by entering an authorization code sent to their
                        email.
                    </td>
                </tr>
                <tr>
                    <th class="text-end">
                        Allow Users to Save Devices for Future Login
                    </th>
                    <td>
                        Allowing a user to save devices will bypass the 2FA
                        challenge on future visits with the same device for 180
                        days.
                    </td>
                </tr>
                <!-- TODO - Add instructions for setting up O365 OATH -->
            </tbody>
        </table>
    </div>
</template>
