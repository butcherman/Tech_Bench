<template>
    <span
        class="badge rounded-pill pointer mx-1"
        :class="bgVariant"
        title="Copy to Clipboard"
        v-tooltip
        @click="copyToClipboard"
    >
        <fa-icon icon="copy" />
    </span>
</template>

<script setup lang="ts">
import { ref } from "vue";

const props = defineProps<{
    value: any | any[];
}>();

const bgVariant = ref("bg-warning");

const copyToClipboard = () => {
    navigator.clipboard.writeText(props.value);

    bgVariant.value = "bg-success";
    setTimeout(() => (bgVariant.value = "bg-warning"), 5000);
};
</script>
