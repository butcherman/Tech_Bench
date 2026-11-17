<script setup lang="ts">
import { computed } from "vue";

const props = defineProps<{
    errorMessage?: string;
    helpMessage?: string;
    helpVisible?: boolean;
    hasFocus: boolean;
}>();

const showHelp = computed(() => (props.helpVisible ? true : props.hasFocus));
</script>

<template>
    <div class="flex w-full flex-col">
        <slot />
        <div class="text-xs text-danger">{{ errorMessage }}</div>
        <div v-if="showHelp" class="text-sm text-muted">{{ helpMessage }}</div>
        <slot name="message" />
    </div>
</template>
