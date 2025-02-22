<script setup lang="ts">
import { ref } from "vue";

const props = defineProps<{
    value: any | any[];
    title?: string;
}>();

const bgVariant = ref<string>("bg-primary");

const copyToClipboard = (): void => {
    navigator.clipboard.writeText(props.value);

    bgVariant.value = "bg-success";
    setTimeout(() => (bgVariant.value = "bg-warning"), 5000);
};
</script>

<template>
    <span
        class="badge rounded-pill pointer mx-1"
        :class="bgVariant"
        :title="title || `Copy to Clipboard`"
        v-tooltip
        @click="copyToClipboard"
    >
        <fa-icon icon="copy" />
    </span>
</template>
