<template>
    <div>
        <Head title="Backup Settings" />
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <BackupSettingsForm :backup-settings="backupSettings" />
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Backup Cleanup Policy:</div>
                        <p>Keep all backups for 7 days</p>
                        <p>Keep Daily backup for 14 days</p>
                        <p>Keep Weekly backups for 8 weeks</p>
                        <p>Keep Monthly backups for 6 months</p>
                        <p>Keep Yearly backups for 2 years</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import BackupSettingsForm from "@/Forms/Admin/Maintenance/BackupSettingsForm.vue";

defineProps<{
    backupSettings: backupSettings;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
