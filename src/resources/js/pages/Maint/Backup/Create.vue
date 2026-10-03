<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import SystemBackupUpload from "@/features/maintenance/components/SystemBackupUpload.vue";

const props = defineProps<{}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex justify-center">
        <SystemBackupUpload />
    </div>
</template>
