<template>
    <span v-tooltip="tipText" @click="copyToClipboard">
        <BaseBadge icon="copy" :variant="bgVariant" />
    </span>
</template>

<script setup lang="ts">
import BaseBadge from "./Badges/BaseBadge.vue";
import { ref } from "vue";

type variant =
    | "danger"
    | "dark"
    | "error"
    | "help"
    | "info"
    | "light"
    | "primary"
    | "secondary"
    | "success"
    | "warning";

const emit = defineEmits<{
    copied: [];
}>();

const props = defineProps<{
    tooltip?: string;
    value: any | any[];
}>();

const tooltipBase = "Copy to Clipboard";

const bgVariant = ref<variant>("info");
const tipText = ref<string>(props.tooltip ?? tooltipBase);

/**
 * Copy the value to clipboard.  Change background of button temporarily.
 */
const copyToClipboard = (): void => {
    navigator.clipboard.writeText(props.value);

    emit("copied");
    bgVariant.value = "success";
    tipText.value = "Success";

    setTimeout(() => {
        bgVariant.value = "warning";
        tipText.value = props.tooltip ?? tooltipBase;
    }, 5000);
};
</script>
