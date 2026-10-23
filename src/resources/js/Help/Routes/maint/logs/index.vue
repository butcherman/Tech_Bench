<script setup lang="ts">
const title = "Application Logs";

const getTitle = () => {
    return title;
};

defineExpose({ getTitle });
</script>

<template>
    <div class="flex flex-col items-center">
        <p>
            All application logs can be viewed from this page. Select one of the
            Log Channels to view the logs for that specific channel.
        </p>
    </div>
</template>
