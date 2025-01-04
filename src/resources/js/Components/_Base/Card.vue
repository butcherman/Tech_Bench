<template>
    <Card pt:body:class="grow flex" pt:content:class="h-full flex flex-col">
        <template #content>
            <div v-if="title || $slots.title" class="text-muted border-b mb-5">
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
        </template>
    </Card>
</template>

<script setup lang="ts">
import { Card } from "primevue";

defineProps<{
    title?: string;
}>();
</script>
