<script setup lang="ts">
const title = "Generate CSR Request";

const getTitle = () => {
    return title;
};

defineExpose({ getTitle });
</script>

<template>
    <div class="flex flex-col items-center">
        <p class="text-center">
            Fill out the form to generate a new private key and CSR Request.
        </p>
    </div>
</template>
