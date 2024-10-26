<template>
    <div class="text-center">
        <h5>User Roles</h5>
        <p>User Permissions are controlled through User Roles.</p>
        <p>
            Select one of the existing Roles to view its allowed permissions,
            update or copy the permissions into a new Role.
        </p>
        <p>
            To create a new Role, select the
            <AddButton pill small>Create New Role</AddButton> button.
        </p>
        <p>
            <strong>Note:</strong> The default built-in roles can be viewed, but
            cannot be modified or deleted.
        </p>
    </div>
</template>

<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
</script>
