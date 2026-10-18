<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";

defineOptions({ layout: AppLayout });

const props = defineProps<{}>();
</script>

<template>
    <div class="flex flex-col gap-2">
        <h1>Dashboard</h1>
    </div>
</template>
