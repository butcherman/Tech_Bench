<script setup lang="ts">
import BaseButton from "./BaseButton.vue";

const props = defineProps<{
    flat?: boolean;
    href?: string;
    icon?: string;
    pill?: boolean;
    text?: string;
    variant?: elementVariant;
}>();
</script>

<template>
    <BaseButton v-bind="props" :variant="variant ?? 'warning'">
        <slot>
            <fa-icon :icon="icon ?? 'pencil'" />
            {{ text ?? "Edit" }}
        </slot>
    </BaseButton>
</template>
