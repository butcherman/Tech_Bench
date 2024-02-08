<template>
    <div class="text-center">
        <h5>User Details</h5>
        <p>
            Specific details about the user are shown here. Use the Actions
            buttons to make changes to the users profile.
        </p>
        <div class="table-responsive">
            <table class="table">
                <tr>
                    <th class="border-end">
                        <button class="btn bg-primary w-100">
                            Resend Welcome Email
                        </button>
                    </th>
                    <td>
                        This button will create a link to help the user setup
                        their account for the first time by creating a new
                        password
                        <br />
                        <strong>Note:</strong> This will only show up if the
                        user has never logged into their account.
                    </td>
                </tr>
                <tr>
                    <th class="border-end">
                        <button class="btn bg-warning w-100">
                            Send Reset Password Link
                        </button>
                    </th>
                    <td>
                        This button will send an email to the user the includes
                        instructions on how to reset their password. The user
                        must have access to their email in order to access this
                        link.
                    </td>
                </tr>
                <tr>
                    <th class="border-end">
                        <button class="btn bg-info w-100">
                            Update User Information
                        </button>
                    </th>
                    <td>
                        This button will take you to the Edit User Details page.
                    </td>
                </tr>
                <tr>
                    <th class="border-end">
                        <button class="btn bg-danger w-100">
                            Disable User
                        </button>
                    </th>
                    <td>
                        This will immediately disable the user and log them out
                        of any active sessions they may be in.
                    </td>
                </tr>
            </table>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{}>();
</script>
