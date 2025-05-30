<script setup lang="ts">
const title = "Error";

const getTitle = () => {
    return title;
};

defineExpose({ getTitle });
</script>

<template>
    <div>
        <img src="/images/error/oops.png" class="mx-auto" />
        <p class="text-center">
            Well this is embarrassing, I am having trouble loading your Help
            Page.
        </p>
        <p class="text-center">Please try again later.</p>
    </div>
</template>
