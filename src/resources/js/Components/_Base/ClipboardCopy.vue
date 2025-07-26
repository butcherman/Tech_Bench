<script setup lang="ts">
import BaseBadge from "./Badges/BaseBadge.vue";
import { computed, ref } from "vue";

const emit = defineEmits<{
    copied: [];
}>();

const props = defineProps<{
    icon?: string;
    tooltip?: string;
    value: any | any[];
}>();

const tooltipBase = "Copy to Clipboard";

const bgVariant = ref<elementVariant>("info");
const tipText = ref<string>(props.tooltip ?? tooltipBase);

const copyIcon = computed(() => props.icon ?? "copy");

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

<template>
    <span v-tooltip.below="tipText" @click="copyToClipboard">
        <BaseBadge :icon="copyIcon" :variant="bgVariant" />
    </span>
</template>
