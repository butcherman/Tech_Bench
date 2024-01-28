<template>
    <div>
        <h5 class="text-center">User Settings</h5>
        <p class="text-center">
            User Settings allows you to adjust your personal settings to your
            liking. The following sections can be updated as necessary.
        </p>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h6>My Account:</h6>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>First Name</th>
                                <td>Modify or change your first name</td>
                            </tr>
                            <tr>
                                <th>Last Name</th>
                                <td>Modify or change your last name</td>
                            </tr>
                            <tr>
                                <th>Email Address</th>
                                <td>
                                    Modify or change your email.
                                    <div class="text-small text-danger">
                                        Note: A confirmation email will be sent
                                        if your email address is changed, even
                                        if Email Notification have been
                                        disabled.
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <h6>My Settings:</h6>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>Receive Email Notifications</th>
                                <td>
                                    Allow emails to be sent to your email
                                    account
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <h6>My Devices:</h6>
                <div class="border p-2">
                    <p>
                        When Using Two-Factor Authentication, all registered
                        devices (using the Remember Device flag) are listed
                        here.
                    </p>
                    <p>
                        Click the
                        <fa-icon icon="xmark" class="text-danger" /> to remove a
                        device forcing Two-Factor Authentication on the next
                        login.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
