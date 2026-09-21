<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import SystemBackupRecent from "@/features/maintenance/components/SystemBackupRecent.vue";

const props = defineProps<{
    backups: BackupInfo[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex justify-center">
        <SystemBackupRecent :backups shown-all />
    </div>
</template>
