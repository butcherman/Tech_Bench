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
    <BaseButton v-bind="props" :variant="variant ?? 'warning'">
        <slot>
            <fa-icon
                :icon="icon ?? 'triangle-exclamation'"
                class="text-danger"
            />
            {{ text }}
        </slot>
    </BaseButton>
</template>
