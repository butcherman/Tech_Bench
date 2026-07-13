<script setup lang="ts">
import UserRoleForm from "@/Help/Forms/User/UserRoleForm.vue";

const title = "Help";

const getTitle = () => {
    return title;
};

defineExpose({ getTitle });
</script>

<template>
    <div class="flex flex-col items-center">
        <p class="text-center">
            Use the following form to an existing User Role that can be assigned
            to users. This will determine the permissions that the user has
            throughout the Tech Bench.
        </p>
        <UserRoleForm />
    </div>
</template>
