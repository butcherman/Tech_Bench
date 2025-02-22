<script setup lang="ts">
import BaseButton from "./BaseButton.vue";

const props = defineProps<{
    flat?: boolean;
    href?: string;
    icon?: string;
    pill?: boolean;
    text?: string;
    variant?:
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
