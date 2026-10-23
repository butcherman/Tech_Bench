<script setup lang="ts">
import TableStacked from "@/Components/_Base/TableStacked.vue";

const title = "Help";

const userActions = {
    reset_users_password: `Manually enter a new password for the user.`,
    send_reset_password_link: `This will create a link for the user to reset
        their own password.`,
    update_user_information: `Update User Profile information`,
    disable_user: `Immediately deactivate the user. Profile will not be deleted,
        but the user will not be able to access their account.`,
};

const getTitle = () => {
    return title;
};

defineExpose({ getTitle });
</script>

<template>
    <div class="flex flex-col items-center">
        <p>
            Specific details about the user are shown here. Use the Actions
            buttons to make changes to the users profile.
        </p>
        <TableStacked :items="userActions" />
    </div>
</template>
