<script setup lang="ts">
const title = "Onboarding Workbooks";

const getTitle = () => {
    return title;
};

defineExpose({ getTitle });
</script>

<template>
    <div class="text-center">
        <p>
            Drag an Element into the Workbook Canvas to start building the
            workbook. Click on the Edit icon to modify the data for that
            element.
        </p>
    </div>
</template>
