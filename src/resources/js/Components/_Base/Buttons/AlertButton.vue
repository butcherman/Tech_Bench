<script setup lang="ts">
import BaseButton from "./BaseButton.vue";

defineProps<{
    tooltip?: string;
}>();
</script>

<template>
    <BaseButton size="small" variant="none" flat v-tooltip="tooltip">
        <slot>
            <fa-icon icon="triangle-exclamation" class="text-warning" />
        </slot>
    </BaseButton>
</template>
