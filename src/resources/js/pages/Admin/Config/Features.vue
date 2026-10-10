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
            <template #title>
                <div class="flex gap-2">
                    <div class="flex items-center">
                        <h1>
                            <fa-icon icon="gears" />
                        </h1>
                    </div>
                    <div class="grow">
                        <h3 class="text-black">App Features</h3>
                        <p class="text-muted">
                            Enable and Disabled Application Features.
                        </p>
                    </div>
                </div>
            </template>
            <AppFeatureForm :featureList />
        </Card>
    </div>
</template>
