<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";

const title = "Help";

const getTitle = () => {
    return title;
};

defineExpose({ getTitle });
</script>

<template>
    <div class="flex flex-col items-center">
        <p>User Permissions are controlled through User Roles.</p>
        <p>
            Select one of the existing Roles to view its allowed permissions,
            update or copy the permissions into a new Role.
        </p>
        <p>
            To create a new Role, select the
            <AddButton pill size="small" text="Create Role" /> button.
        </p>
        <p>
            <strong>Note:</strong> The default built-in roles can be viewed, but
            cannot be modified or deleted.
        </p>
    </div>
</template>
