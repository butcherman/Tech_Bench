<script setup lang="ts">
const title = "About Tech Bench";

const getTitle = () => {
    return title;
};

defineExpose({ getTitle });
</script>

<template>
    <div class="text-center">
        <p>
            The About page shows the current version of the Tech Bench
            Application.
        </p>
    </div>
</template>
