<script setup lang="ts">
import SystemBackupConfiguration from "./SystemBackupConfiguration.vue";
import SystemBackupRecent from "./SystemBackupRecent.vue";
import SystemBackupStatus from "./SystemBackupStatus.vue";
import SystemBackupStorage from "./SystemBackupStorage.vue";

const props = defineProps<{
    status: BackupStatus;
    backups: BackupInfo[];
    nextRun: string;
    strategy: BackupStrategy;
    settings: BackupSettings;
    storage: StorageUsage;
}>();
</script>

<template>
    <div class="flex flex-col gap-3">
        <SystemBackupStatus :status :next-run :strategy />
        <div class="flex gap-3">
            <SystemBackupConfiguration :settings :strategy />
            <SystemBackupStorage :storage :status />
        </div>
        <SystemBackupRecent :backups />
    </div>
</template>
