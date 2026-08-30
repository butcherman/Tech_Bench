<script setup lang="ts">
import AppFeatureForm from "@/features/administration/forms/AppFeatureForm.vue";
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";

defineProps<{
    featureList: AppFeatureList;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex justify-center">
        <Card size="md">
            <AppFeatureForm :featureList />
        </Card>
    </div>
</template>
