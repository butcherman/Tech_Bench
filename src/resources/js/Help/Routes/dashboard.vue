<script setup lang="ts">
const title = "Dashboard Help";

const getTitle = () => {
    return title;
};

defineExpose({ getTitle });
</script>

<template>
    <div class="text-center">
        <h5>Dashboard</h5>
        <p>
            The Dashboard is your Home Page for the Tech Bench. This page will
            show your current notifications and any other important information
            or shortcuts.
        </p>
    </div>
</template>
