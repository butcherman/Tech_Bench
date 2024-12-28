<template>
    <v-btn
        prepend-icon="plus"
        base-color="blue"
        :size="size ?? 'default'"
        :rounded="pill"
    >
        {{ text ?? "Add" }}
    </v-btn>
</template>

<script setup lang="ts">
defineProps<{
    pill?: boolean;
    size?: "x-small" | "small" | "default" | "large" | "x-large";
    text?: string;
}>();
</script>
