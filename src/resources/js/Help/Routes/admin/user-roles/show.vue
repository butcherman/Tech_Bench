<script setup lang="ts">
const title = "Help";

const getTitle = () => {
    return title;
};

defineExpose({ getTitle });
</script>

<template>
    <div class="flex flex-col items-center">
        <p>
            Permissions that are listed with a
            <fa-icon icon="check" class="text-success" />
            are allowed by this Role.
        </p>
        <p>
            Permissions that are listed with a
            <fa-icon icon="xmark" class="text-danger" />
            are not allowed by this Role.
        </p>
        <p>
            You can make a duplicate copy of the Role, Edit its current
            Permissions or Delete the role by using the buttons at the bottom of
            the page.
        </p>
        <p>
            <strong>Note 1:</strong> The built in Default Roles cannot be
            modified or deleted.
        </p>
        <p>
            <strong>Note 2:</strong> If a Role is currently assigned to at least
            one user, it cannot be deleted.
        </p>
    </div>
</template>
