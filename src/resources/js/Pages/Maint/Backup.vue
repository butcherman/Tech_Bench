<template>
    <div>
        <Head title="Backup Tech Bench" />
        <div class="row justify-content-center my-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div v-if="inProgress">
                            <button class="btn btn-danger w-100">
                                A backup is currently running
                            </button>
                        </div>
                        <Link
                            v-else
                            as="button"
                            :href="$route('maint.backup.store')"
                            method="post"
                            class="btn btn-info w-100"
                            :disabled="inProgress"
                        >
                            Run Backup
                        </Link>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center my-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <Link
                            as="button"
                            :href="$route('maint.backups.settings.show')"
                            method="post"
                            class="btn btn-info w-100"
                        >
                            Backup Settings
                        </Link>
                    </div>
                </div>
            </div>
        </div>
        <div
            v-if="backupMessages.length"
            class="row justify-content-center"
            id="process-output"
        >
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <p v-for="msg in backupMessages" class="p-0 m-0 mh-25">
                            {{ msg }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center my-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <RefreshButton :only="['backup-list']" />
                            Backup Files
                        </div>
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
import { onMounted, ref } from "vue";

const props = defineProps<{
    backupRunning: boolean;
    backupList: any[];
}>();

const inProgress = ref(props.backupRunning);

onMounted(() => {
    console.log("mounted");
    Echo.private("administration-channel").listen(
        ".AdministrationEvent",
        (msg: { msg: string }) => {
            backupMessages.value.push(msg.msg);

            if (msg.msg === "Backup Process Called") {
                inProgress.value = true;
            } else if (msg.msg === "Backup completed!") {
                inProgress.value = false;
            }
        }
    );
});

const deleteBackup = (fileName: string) => {
    verifyModal("This cannot be undone").then((res) => {
        if (res) {
            router.delete(route("maint.backup.destroy", fileName));
        }
    });
};

const backupMessages = ref<string[]>([]);

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

<style scoped lang="scss">
#process-output {
    min-height: 250px;
}
</style>
