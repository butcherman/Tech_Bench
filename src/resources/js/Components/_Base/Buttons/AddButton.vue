<script setup lang="ts">
import BaseButton from "./BaseButton.vue";

const props = defineProps<{
    flat?: boolean;
    href?: string;
    icon?: string;
    pill?: boolean;
    size?: "small" | "normal" | "large";
    text?: string;
    variant?: elementVariant;
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
