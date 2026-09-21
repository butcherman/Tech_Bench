<script setup lang="ts">
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import Card from "@/core/components/Card.vue";
import DataTable from "@/core/features/dataResources/DataTable.vue";
import Drawer from "@/core/components/Drawer.vue";
import prettyBytes from "pretty-bytes";
import verifyModal from "@/core/features/verifyModal";
import { computed, ref } from "vue";
import { deleteMethod } from "@/wayfinder/routes/maint/backups";
import { download } from "@/wayfinder/routes/maint/backups";
import { router } from "@inertiajs/vue3";
import { useColumnBuilder } from "@/core/features/dataResources/composables/columnBuilder";

const props = defineProps<{
    backups: BackupInfo[];
}>();

const showInfoDrawer = ref<boolean>(false);
const activeBackup = ref<BackupInfo>();

const showBackupInfo = (event: MouseEvent, info: BackupInfo) => {
    activeBackup.value = info;
    showInfoDrawer.value = true;
};

const colHelper = useColumnBuilder<BackupInfo>();

const tableColumns = [
    colHelper.text("backup_name", "Name", {
        filterable: false,
        sort: false,
    }),
    colHelper.text("type", "Type", {
        filterable: false,
        sort: false,
    }),
    colHelper.text("size", "Size", {
        filterable: false,
        sort: false,
        formatter: (value: number) => prettyBytes(value ?? 0),
    }),
    colHelper.text("status", "Status", {
        filterable: false,
        sort: false,
    }),
];

const stateIcon = computed(() => {
    return {
        completed: "check",
        failed: "xmark",
        running: "microchip",
    }[activeBackup.value?.status ?? "failed"];
});

const stateColor = computed(() => {
    return {
        completed: "text-success",
        failed: "text-danger",
        running: "text-warning",
    }[activeBackup.value?.status ?? "failed"];
});

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
</script>

<template>
    <Card title="Recent Backups">
        <DataTable
            :columns="tableColumns"
            :data="backups"
            :row-click-fn="showBackupInfo"
            compact
        />
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
                                    :icon="stateIcon"
                                    :class="stateColor"
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
                            <td>{{ activeBackup.duration }}</td>
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
                </div>
            </div>
        </Drawer>
    </Card>
</template>
