<script setup lang="ts">
import { computed } from "vue";
import { useVariantHelper } from "@/core/composables/variantHelper";

const props = defineProps<{
    text: string;
    variant: VariantType;
}>();

const { getVariantClass, getVariantIcon } = useVariantHelper();

const bgClass = computed(() => getVariantClass(props.variant));
const icon = computed(() => getVariantIcon(props.variant));
</script>

<template>
    <div
        class="flex py-2 px-4 rounded-lg justify-between opacity-90"
        :class="bgClass"
    >
        <div><fa-icon :icon="icon" /></div>
        <div>{{ text }}</div>
        <div><fa-icon :icon="icon" /></div>
    </div>
</template>
