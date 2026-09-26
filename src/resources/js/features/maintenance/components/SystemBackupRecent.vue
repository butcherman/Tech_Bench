<script setup lang="ts">
import BaseBadge from "@/core/components/badges/BaseBadge.vue";
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import Card from "@/core/components/Card.vue";
import DataTable from "@/core/features/dataResources/DataTable.vue";
import Drawer from "@/core/components/Drawer.vue";
import prettyBytes from "pretty-bytes";
import prettyMs from "pretty-ms";
import verifyModal from "@/core/features/verifyModal";
import { computed, ref } from "vue";
import { create } from "@/wayfinder/routes/maint/backups/upload";
import { deleteMethod } from "@/wayfinder/routes/maint/backups";
import { download } from "@/wayfinder/routes/maint/backups";
import { router } from "@inertiajs/vue3";
import { showAll } from "@/wayfinder/routes/maint/backups";
import { useColumnBuilder } from "@/core/features/dataResources/composables/columnBuilder";
import { restore } from "@/wayfinder/routes/maint/backups";

const props = defineProps<{
    backups: BackupInfo[];
    shownAll?: boolean;
}>();

const cardTitle = computed(() =>
    props.shownAll ? "All Backups" : "Recent Backups",
);

const showInfoDrawer = ref<boolean>(false);
const activeBackup = ref<BackupInfo>();

const showBackupInfo = (event: MouseEvent, info: BackupInfo) => {
    activeBackup.value = info;
    showInfoDrawer.value = true;
};

const colHelper = useColumnBuilder<BackupInfo>();

const tableColumns = [
    colHelper.text("backup_name", "Name", {
        filterable: props.shownAll,
        sort: props.shownAll,
    }),
    colHelper.text("type", "Type", {
        filterable: props.shownAll,
        filterSelect: props.shownAll,
        sort: props.shownAll,
    }),
    colHelper.text("size", "Size", {
        filterable: props.shownAll,
        sort: props.shownAll,
        formatter: (value: number) => prettyBytes(value ?? 0),
    }),
    colHelper.text("status", "Status", {
        filterable: false,
        sort: props.shownAll,
    }),
];

const getStateIcon = (state: BackupState): string => {
    return {
        completed: "check",
        failed: "xmark",
        running: "microchip",
    }[state];
};

const getStateColor = (state: BackupState): string => {
    return {
        completed: "text-success",
        failed: "text-danger",
        running: "text-warning",
    }[state];
};

const deleteBackup = () => {
    verifyModal("This cannot be undone").then((res) => {
        if (res && activeBackup.value) {
            router.delete(deleteMethod.url(activeBackup.value?.backup_name), {
                preserveScroll: true,
                onFinish: () => (showInfoDrawer.value = false),
            });
        }
    });
};

const restoreBackup = () => {
    console.log("restore");
    verifyModal(
        "All existing data will be replace with the data in this backup.  Only continue if you are sure you want to replace all data",
        "DANGER!!!",
    ).then((res) => {
        console.log(res);
        if (res) {
            router.put(restore.url(), {
                selected: activeBackup.value?.backup_name,
            });
        }
    });
};
</script>

<template>
    <Card :title="cardTitle">
        <template #append-title>
            <BaseBadge
                v-if="!shownAll"
                :href="showAll.url()"
                text="Show All"
                size="sm"
                class="me-2"
            />
            <BaseBadge :href="create.url()" text="Upload" size="sm" />
        </template>
        <DataTable
            :columns="tableColumns"
            :data="backups"
            :row-click-fn="showBackupInfo"
            compact
        >
            <template #row.status="{ rowData }">
                <div class="text-center w-full">
                    <fa-icon
                        :icon="getStateIcon(rowData.status)"
                        :class="getStateColor(rowData.status)"
                    />
                </div>
            </template>
        </DataTable>
        <Drawer
            v-model="showInfoDrawer"
            position="right"
            title="Backup Details"
        >
            <div v-if="activeBackup">
                <table>
                    <tbody>
                        <tr>
                            <th class="text-start pe-3 py-3">Status</th>
                            <td class="capitalize">
                                <fa-icon
                                    :icon="getStateIcon(activeBackup.status)"
                                    :class="getStateColor(activeBackup.status)"
                                />
                                {{ activeBackup.status }}
                            </td>
                        </tr>
                        <tr v-if="activeBackup.error">
                            <th class="text-start pe-3 py-3">Error Message</th>
                            <td class="text-danger">
                                {{ activeBackup.error }}
                            </td>
                        </tr>
                        <tr>
                            <th class="text-start pe-3 py-3">Triggered By</th>
                            <td class="capitalize">{{ activeBackup.type }}</td>
                        </tr>
                        <tr>
                            <th class="text-start pe-3 py-3">Created</th>
                            <td>{{ activeBackup.started_at }}</td>
                        </tr>
                        <tr>
                            <th class="text-start pe-3 py-3">Size</th>
                            <td>{{ prettyBytes(activeBackup.size) }}</td>
                        </tr>
                        <tr>
                            <th class="text-start pe-3 py-3">Duration</th>
                            <td>
                                {{ prettyMs(activeBackup.duration * 1000) }}
                            </td>
                        </tr>
                        <tr>
                            <th class="text-start pe-3 py-3">File Name</th>
                            <td>{{ activeBackup.backup_name }}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="flex gap-2 justify-center">
                    <a :href="download.url(activeBackup.backup_name)">
                        <BaseButton text="Download" size="sm" />
                    </a>
                    <BaseButton
                        text="Delete"
                        size="sm"
                        variant="danger"
                        @click="deleteBackup"
                    />
                    <BaseButton
                        text="Restore This Backup"
                        size="sm"
                        variant="error"
                        @click="restoreBackup"
                    />
                </div>
            </div>
        </Drawer>
    </Card>
</template>
