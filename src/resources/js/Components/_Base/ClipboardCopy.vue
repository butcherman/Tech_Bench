<template>
    <span v-tooltip="tipText ?? 'Copy to Clipboard'" @click="copyToClipboard">
        <BaseBadge icon="copy" :variant="bgVariant" />
    </span>
</template>

<script setup lang="ts">
import { ref } from "vue";
import BaseBadge from "./Badges/BaseBadge.vue";

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

const emit = defineEmits(["copied"]);
const props = defineProps<{
    value: any | any[];
    tooltip?: string;
}>();

const tooltipBase = "Copy to Clipboard";

const bgVariant = ref<variant>("info");
const tipText = ref(props.tooltip ?? tooltipBase);

const copyToClipboard = () => {
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
