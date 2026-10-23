<script setup lang="ts">
const title = "Help";

const getTitle = () => {
    return title;
};

defineExpose({ getTitle });
</script>

<template>
    <div class="flex flex-col items-center">
        <p>
            Users that no longer have access to the Tech Bench are listed here.
        </p>
        <p>Click on a user to view additional information and options.</p>
    </div>
</template>
