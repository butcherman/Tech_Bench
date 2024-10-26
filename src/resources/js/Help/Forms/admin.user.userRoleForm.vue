<template>
    <div class="table-responsive">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th class="text-end">Role Name</th>
                    <td>
                        Select a name for this Role.
                        <br />
                        <strong>Note:</strong> The name must be unique and not
                        already exist in the database.
                    </td>
                </tr>
                <tr>
                    <th class="text-end">Description:</th>
                    <td>
                        Input a general description for this Role so it can be
                        easily identified by other Administrators.
                    </td>
                </tr>
                <tr>
                    <th class="text-end">Permissions:</th>
                    <td>
                        For each of the permissions, select if the users
                        assigned to this role should be allowed to perform the
                        selected task.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
