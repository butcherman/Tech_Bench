<script setup lang="ts">
import BaseButton from "./BaseButton.vue";

const props = defineProps<{
    async?: boolean;
    flat?: boolean;
    href?: string;
    icon?: string;
    pill?: boolean;
    text?: string;
    size?: ComponentSize;
    variant?: VariantType;
}>();
</script>

<template>
    <BaseButton v-bind="props" :variant="variant ?? 'info'">
        <slot>
            <fa-icon :icon="icon ?? 'plus'" />
            {{ text ?? "Add" }}
        </slot>
    </BaseButton>
</template>
