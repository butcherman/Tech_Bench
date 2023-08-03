<template>
    <div>
        <Head title="Backups" />
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <button
                            class="btn btn-info w-100"
                            @click="mountBackupProcess"
                            :disabled="running"
                        >
                            <span
                                v-if="running"
                                class="spinner-border spinner-border-sm"
                            />
                            Run Local Backup
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Backup Output:</div>
                        <div class="border" id="backup-output-box">
                            <pre ref="backupOutput" class="m-2">
No Backup Running
                            </pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            Backups:
                            <button
                                type="button"
                                class="btn btn-sm float-end"
                                title="Refresh"
                                v-tooltip
                                @click="fetchBackups"
                            >
                                <fa-icon icon="fa-rotate" :spin="loading" />
                            </button>
                        </div>
                        <div>
                            <ul class="list-group">
                                <li
                                    v-for="backup in backupList"
                                    class="list-group-item"
                                >
                                    {{ backup }}
                                    <span
                                        class="float-end pointer text-danger mx-1"
                                        title="Delete"
                                        v-tooltip
                                        @click="deleteBackup(backup)"
                                    >
                                        <fa-icon icon="trash-can" />
                                    </span>
                                    <span
                                        class="float-end pointer text-info mx-1"
                                        title="Download"
                                        v-tooltip
                                        @click="downloadBackup(backup)"
                                    >
                                        <fa-icon icon="download" />
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import axios from "axios";
import { echo } from "@/State/LayoutState";
import { ref, onMounted, onUnmounted } from "vue";
import ok from "@/Modules/ok";
import verify from "@/Modules/verify";

const backupOutput = ref<InstanceType<typeof HTMLElement> | null>(null);
const running = ref<boolean>(false);
const loading = ref<boolean>(false);
const backupList = ref<string[]>([]);

interface processData {
    message: string;
    completed: boolean;
}

/**
 * On Mount, subscribe to the Backup Status Channel.
 */
onMounted(() => {
    fetchBackups();

    echo.private("process.backup").listen(".BroadcastBackupStatus", (data: processData) => {
        // Echo the current status of the backup
        if (backupOutput.value) {
            backupOutput.value.innerHTML += `${data.message}\n`;
        }

        // Trigger any post-backup processes
        if (data.completed) {
            running.value = false;
            fetchBackups();
        }
    });
});

/**
 * On Unmount, leave the Backup Status Channel
 */
onUnmounted(() => echo.leaveChannel("process.backup"));

/**
 * Run a Manual Backup
 */
const mountBackupProcess = (): void => {
    axios
        .put(route("admin.backups.run"))
        .then((res) => {
            if (res.status === 204 && backupOutput.value) {
                backupOutput.value.innerHTML = "Starting Backup...\n\n";
                running.value = true;
            }
        });
};

/**
 * Fetch all backups
 */
const fetchBackups = (): void => {
    loading.value = true;
    axios.get(route("admin.backups.fetch")).then((res) => {
        backupList.value = res.data;
        loading.value = false;
    });
};

/**
 * Download a selected backup
 */
const downloadBackup = (backup: string): void => {
    axios.get(route("admin.backups.download", backup)).catch((err) => {
        ok(err.response.data.message);
    });
};

/**
 * Delete a selected backup
 */
const deleteBackup = (backup: string): void => {
    verify({ message: "This cannot be undone" }).then((res) => {
        if (res) {
            axios
                .delete(route("admin.backups.destroy", backup))
                .then(() => fetchBackups())
                .catch((err) => {
                    console.log(err);
                    ok(err.response.data.message);
                });
        }
    });
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<style scoped lang="scss">
#backup-output-box {
    height: 250px;
}
</style>
