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
                        <p class="my-0 ms-4">Keep All backups for 7 days</p>
                        <p class="my-0 ms-4">Keep Daily backup for 14 days</p>
                        <p class="my-0 ms-4">Keep Weekly backups for 8 weeks</p>
                        <p class="my-0 ms-4">
                            Keep Monthly backups for 6 months
                        </p>
                        <p class="my-0 ms-4">Keep Yearly backups for 2 years</p>
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
