<script setup lang="ts">
import DeleteButton from "@/Components/_Base/Buttons/DeleteButton.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
const title = "Tech Bench Logo";

const getTitle = () => {
    return title;
};

defineExpose({ getTitle });
</script>

<template>
    <div class="flex flex-col items-center">
        <p>Use this form to change the default Tech Bench Logo.</p>
        <p>
            Drag new logo image to input box and select
            <BaseButton text="Upload Logo" size="small" />
        </p>
        <p>
            To remove the custom logo and go back to the default logo, select
            the <DeleteButton text="Delete Logo" size="small" /> button.
        </p>
    </div>
</template>
