<template>
    <TableStacked :rows="formData" title-case />
</template>

<script setup lang="ts">
import TableStacked from "@/Components/_Base/TableStacked.vue";

const formData = {
    enable_nightly_backups: `With this option enabled, a backup will be automatically
                             created every night at 3am.`,
    enable_auto_backup_cleanup: `With this option enabled, backups not meeting the
                                 proper criteria will automatically be removed.`,
    encrypt_backup: `If needed, the zip file created by the backup process can be
                     encrypted for additional security.`,
    encryption_password: `Enter a secure password to use on the backup zip file.`,
    email_notifications_to: `To get email notices on successful or failed backups,
                             enter a valid email address here.`,
};
</script>
