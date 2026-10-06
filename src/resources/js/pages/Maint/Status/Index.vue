<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import ContainerStatus from "@/features/maintenance/components/ContainerStatus.vue";

const props = defineProps<{
    summary: ContainerSummary[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex gap-2 justify-center">
        <ContainerStatus :summary />
    </div>
</template>
