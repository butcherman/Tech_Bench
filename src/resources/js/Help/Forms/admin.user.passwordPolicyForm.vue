<template>
    <div class="table-responsive">
        <table class="table">
            <tbody>
                <tr>
                    <th class="text-end">Password Expires in Days</th>
                    <td>
                        Enter the number of days before a user is forced to
                        update their password.
                        <br />
                        <strong>Note</strong> Enter 0 to set passwords to never
                        expire.
                    </td>
                </tr>
                <tr>
                    <th class="text-end">Password Minimum Length</th>
                    <td>
                        Passwords must have at least this amount of characters
                        in them
                    </td>
                </tr>
                <tr>
                    <th class="text-end">Uppercase Letter</th>
                    <td>Password must contain at least one uppercase letter</td>
                </tr>
                <tr>
                    <th class="text-end">Lowercase Letter</th>
                    <td>Password must contain at least one lowercase letter</td>
                </tr>
                <tr>
                    <th class="text-end">Number</th>
                    <td>Password must contain at least one number</td>
                </tr>
                <tr>
                    <th class="text-end">Special Character</th>
                    <td>
                        Password must contain at least one special character
                        (Example: !@#$%^&*)
                    </td>
                </tr>
                <tr>
                    <th class="text-end">
                        Disable Known Compromised Passwords
                    </th>
                    <td>
                        This option uses the
                        <a href="https://haveibeenpwned.com/" target="_blank">
                            https://haveibeenpwned.com/
                        </a>
                        service to determine if the password has appeared at
                        least once in a public data leak.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
