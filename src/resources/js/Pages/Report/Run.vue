<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";

defineProps<{
    template?: string;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div>
        <div v-html="template" />
    </div>
</template>
