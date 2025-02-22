<script setup lang="ts">
defineProps<{
    title?: string;
}>();
</script>

<template>
    <div class="flex flex-col rounded-lg bg-white p-3 shadow-lg">
        <div v-if="title || $slots.title" class="text-muted border-b mb-5 pb-1">
            <slot name="title">
                {{ title }}
                <span class="float-end mb-1">
                    <slot name="append-title" />
                </span>
            </slot>
        </div>
        <div class="grow"><slot /></div>
    </div>
</template>
