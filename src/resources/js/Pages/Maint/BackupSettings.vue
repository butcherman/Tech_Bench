<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import BackupSettingsForm from "@/Forms/Maintenance/BackupSettingsForm.vue";
import Card from "@/Components/_Base/Card.vue";

defineProps<{
    nightly_backup: boolean;
    nightly_cleanup: boolean;
    encryption: boolean;
    password: string | null;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="flex justify-center">
        <Card class="tb-card">
            <BackupSettingsForm
                :nightly_backup="nightly_backup"
                :nightly_cleanup="nightly_cleanup"
                :encryption="encryption"
                :password="password"
            />
        </Card>
    </div>
    <div class="flex justify-center">
        <Card class="tb-card-sm mt-3" title="Backup Cleanup Policy">
            <div class="flex">
                <ul class="mx-auto">
                    <li>Keep All backups for 7 days</li>
                    <li>Keep Daily backup for 14 days</li>
                    <li>Keep Weekly backups for 8 weeks</li>
                    <li>Keep Monthly backups for 6 months</li>
                    <li>Keep Yearly backups for 2 years</li>
                </ul>
            </div>
        </Card>
    </div>
</template>
