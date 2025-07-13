<script setup lang="ts">
defineProps<{
    title?: string;
}>();
</script>

<template>
    <div class="flex flex-col rounded-lg bg-white p-3 shadow-lg">
        <div
            v-if="title || $slots.title"
            class="text-muted border-b mb-5 pb-1 flex"
        >
            <div class="grow">
                <slot name="title">
                    {{ title }}
                </slot>
            </div>
            <div>
                <span class="mb-1">
                    <slot name="append-title" />
                </span>
            </div>
        </div>
        <div class="grow flex flex-col">
            <slot />
        </div>
        <div v-if="$slots.footer" class="border-t mt-5 pt-1">
            <slot name="footer" />
        </div>
    </div>
</template>
