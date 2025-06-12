<script setup lang="ts">
import TableStacked from "@/Components/_Base/TableStacked.vue";

const tableData = {
    enable_nightly_backups: `Backups will be run at daily at 3:00am in the
        timezone selected in the Application Configuration.`,
    enable_auto_backup_cleanup: `Old backup files will be removed following the
        cleanup policy listed on the page.`,
    encrypt_backup: `Backup will be password protected when selected.`,
    encryption_password: `Password used to encrypt the backup file.`,
};
</script>

<template>
    <TableStacked :items="tableData" />
</template>
