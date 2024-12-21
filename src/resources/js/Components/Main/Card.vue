<template>
    <v-card elevation="2" class="py-5 px-3">
        <v-card-subtitle v-if="title || $slots.title" class="border-b">
            <slot name="title">
                {{ title }}
            </slot>
        </v-card-subtitle>
        <slot />
    </v-card>
</template>

<script setup lang="ts">
defineProps<{
    title?: string;
}>();
</script>
