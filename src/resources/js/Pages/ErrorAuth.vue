<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import ErrorMessage from "@/Components/_Base/ErrorMessage.vue";

const props = defineProps<{
    status: number;
    message: string;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="flex justify-center">
        <Card class="tb-card text-center">
            <ErrorMessage v-bind="props" />
        </Card>
    </div>
</template>
