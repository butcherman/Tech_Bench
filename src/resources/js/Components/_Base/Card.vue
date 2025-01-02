<template>
    <Card>
        <template #content>
            <div class="h-full flex flex-col">
                <div
                    v-if="title || $slots.title"
                    class="text-muted border-b flex-none mb-5"
                >
                    <slot name="title">
                        {{ title }}
                        <span class="float-end mb-1">
                            <slot name="append-title" />
                        </span>
                    </slot>
                </div>
                <div class="grow">
                    <slot />
                </div>
            </div>
        </template>
    </Card>
</template>

<script setup lang="ts">
import { Card } from "primevue";

defineProps<{
    title?: string;
}>();
</script>
