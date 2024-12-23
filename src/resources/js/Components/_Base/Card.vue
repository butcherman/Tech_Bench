<template>
    <v-card elevation="2" class="py-5 px-3">
        <div class="h-full flex flex-col">
            <div
                v-if="title || $slots.title"
                class="text-muted border-b flex-none mb-5"
            >
                <slot name="title">
                    {{ title }}
                    <span class="float-end">
                        <slot name="append-title" />
                    </span>
                </slot>
            </div>
            <div class="grow">
                <slot />
            </div>
        </div>
    </v-card>
</template>

<script setup lang="ts">
defineProps<{
    title?: string;
}>();
</script>
