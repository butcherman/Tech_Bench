<script setup lang="ts">
const title = "Workbook Preview";

const getTitle = () => {
    return title;
};

defineExpose({ getTitle });
</script>

<template>
    <div class="text-center">
        <p>Live Preview of the workbook being edited is shown here.</p>
    </div>
</template>
