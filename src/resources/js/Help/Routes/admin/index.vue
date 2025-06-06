<script setup lang="ts">
const title = "Administration";

const getTitle = () => {
    return title;
};

defineExpose({ getTitle });
</script>

<template>
    <div class="text-center">
        <p>Application Administration is entered from this menu.</p>
        <p>
            Select one of the options in the Administration Menu for more
            information.
        </p>
    </div>
</template>
