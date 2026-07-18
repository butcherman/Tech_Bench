<script setup lang="ts">
const title = "Manage File Links";

const getTitle = () => {
    return title;
};

defineExpose({ getTitle });
</script>

<template>
    <div class="flex flex-col items-center">
        <div class="md:w-3/4 text-center">
            <p>All File Links created by all active users are shown here</p>
        </div>
    </div>
</template>
