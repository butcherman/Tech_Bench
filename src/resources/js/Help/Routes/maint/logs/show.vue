<script setup lang="ts">
const title = "Logs";

const getTitle = () => {
    return title;
};

defineExpose({ getTitle });
</script>

<template>
    <div class="flex flex-col items-center">
        <p>
            Individual Log Entries are shown here. Filter by the logging level,
            or download the raw log file.
        </p>
    </div>
</template>
