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
            Onboarding Workbooks allow you to work with your customer to gather
            information needed to implement their equipment. Workbooks can be
            customized as needed. Click on an equipmen type to create or edit
            the workbook.
        </p>
    </div>
</template>
