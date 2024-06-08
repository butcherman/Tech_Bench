<template>
    <div>
        <Head title="Backup Settings" />
        <div class="row justify-content-center my-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Backup Settings</div>
                        <BackupSettingsForm
                            :nightly_backup="nightly_backup"
                            :nightly_cleanup="nightly_cleanup"
                            :encryption="encryption"
                            :password="password"
                            :mail_to="mail_to"
                        />
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center my-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Backup Cleanup Policy:</div>
                        <div class="d-flex justify-content-center">
                            <div>
                                <p class="my-0 ms-4">
                                    Keep All backups for 7 days
                                </p>
                                <p class="my-0 ms-4">
                                    Keep Daily backup for 14 days
                                </p>
                                <p class="my-0 ms-4">
                                    Keep Weekly backups for 8 weeks
                                </p>
                                <p class="my-0 ms-4">
                                    Keep Monthly backups for 6 months
                                </p>
                                <p class="my-0 ms-4">
                                    Keep Yearly backups for 2 years
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import BackupSettingsForm from "@/Forms/Maintenance/BackupSettingsForm.vue";
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{
    nightly_backup: boolean;
    nightly_cleanup: boolean;
    encryption: boolean;
    password: string | null;
    mail_to: string;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
