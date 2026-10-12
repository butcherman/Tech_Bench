<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";

defineOptions({ layout: AppLayout });

const props = defineProps<{}>();
</script>

<template>
    <div>
        <Card v-for="n in 100">Hello World - {{ n }}</Card>
    </div>
</template>
