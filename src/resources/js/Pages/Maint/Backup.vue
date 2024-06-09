<template>
    <div>
        <Head title="Backup Tech Bench" />
        <div class="row justify-content-center my-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div v-if="backupRunning">
                            <p class="text-center">
                                A backup is currently running
                            </p>
                            <p class="text-center">
                                <RefreshButton :only="['backup-running']" />
                                Refresh to try again
                            </p>
                        </div>
                        <Link
                            v-else
                            as="button"
                            :href="$route('maint.backup.store')"
                            method="post"
                            class="btn btn-info w-100"
                            :disabled="backupRunning"
                        >
                            Run Local Backup
                        </Link>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center my-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Backup Files</div>
                        <Table
                            responsive
                            :columns="tableCols"
                            :rows="backupList"
                        >
                            <template #column="{ columnName, rowData }">
                                <span v-if="columnName === 'size'">
                                    {{ prettyBytes(rowData.size) }}
                                </span>
                            </template>
                            <template #action="{ rowData }">
                                <!-- {{ rowData }} -->
                                <a
                                    :href="
                                        $route(
                                            'maint.backup.show',
                                            rowData.name
                                        )
                                    "
                                    class="bg-info badge rounded-pill pointer mx-1"
                                    title="Download Backup"
                                    v-tooltip
                                >
                                    <fa-icon icon="download" />
                                </a>
                                <DeleteBadge
                                    title="Delete Backup"
                                    @click="deleteBackup(rowData.name)"
                                />
                            </template>
                        </Table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import RefreshButton from "@/Components/_Base/Buttons/RefreshButton.vue";
import Table from "@/Components/_Base/Table.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import prettyBytes from "pretty-bytes";
import verifyModal from "@/Modules/verifyModal";
import { router } from "@inertiajs/vue3";

defineProps<{
    backupRunning: boolean;
    backupList: any[];
}>();

const deleteBackup = (fileName: string) => {
    verifyModal("This cannot be undone").then((res) => {
        if (res) {
            router.delete(route("maint.backup.destroy", fileName));
        }
    });
};

const tableCols = [
    {
        label: "Filename",
        field: "name",
    },
    {
        label: "Backup Size",
        field: "size",
    },
];
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
