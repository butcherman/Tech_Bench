<template>
    <div>
        <h5 class="text-center">Edit User Details</h5>
        <p class="text-center">
            The users basic profile information can be edited on this page.
        </p>
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th class="text-end">Username</th>
                        <td>
                            The username is a unique identifier for the user
                            profile. This is what the user will use when logging
                            in.
                        </td>
                    </tr>
                    <tr>
                        <th class="text-end">First Name</th>
                        <td>Users First Name</td>
                    </tr>
                    <tr>
                        <th class="text-end">Last Name</th>
                        <td>Users Last Name</td>
                    </tr>
                    <tr>
                        <th class="text-end">Email Address</th>
                        <td>
                            Email Address for user
                            <br />
                            <strong>Note:</strong>
                            The Email Address must be unique.
                        </td>
                    </tr>
                    <tr>
                        <th class="text-end">Role</th>
                        <td>
                            The Role determines the permissions that the user
                            has. For more information visit the
                            <Link :href="$route('dashboard')">Roles</Link>
                            page.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
