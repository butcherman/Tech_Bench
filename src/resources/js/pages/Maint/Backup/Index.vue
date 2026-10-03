<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import SystemBackup from "@/features/maintenance/components/SystemBackup.vue";

const props = defineProps<{
    status: BackupStatus;
    backups: BackupInfo[];
    nextRun: string;
    strategy: BackupStrategy;
    settings: BackupSettings;
    storage: StorageUsage;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <SystemBackup v-bind="props" />
</template>
