<script setup lang="ts">
import BasicConfigForm from "@/Help/Forms/Config/BasicConfigForm.vue";

const title = "Tech Bench Settings";

const getTitle = () => {
    return title;
};

defineExpose({ getTitle });
</script>

<template>
    <div class="flex flex-col items-center">
        <p>Use this form to modify the basic settings for the Tech Bench.</p>
        <BasicConfigForm />
    </div>
</template>
