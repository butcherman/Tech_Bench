<script setup lang="ts">
import AuthLayout from "@/layouts/AuthLayout.vue";
import Card from "@/core/components/Card.vue";

const props = defineProps<{}>();
</script>

<script lang="ts">
export default { layout: AuthLayout };
</script>
<template>
    <div class="flex justify-center">
        <Card>Hello World</Card>
    </div>
</template>
