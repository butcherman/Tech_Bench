<template>
    <Modal ref="fileDetailsModal" title="Customer File Details">
        <TableStacked
            v-if="activeFile"
            :rows="activeFile"
            :headers="tableHeaders"
        >
            <template #value="{ rowData }">
                <span v-if="rowData.index === 'File Name'">
                    {{ rowData.value.file_name }}
                </span>
                <span v-if="rowData.index === 'File Size'">
                    {{ fileSize }}
                </span>
                <span v-if="rowData.index === 'File For'" v-html="fileFor" />
            </template>
        </TableStacked>
    </Modal>
</template>

<script setup lang="ts">
import Modal from "../_Base/Modal.vue";
import TableStacked from "../_Base/TableStacked.vue";
import prettyBytes from "pretty-bytes";
import { ref, computed } from "vue";

const fileDetailsModal = ref<InstanceType<typeof Modal> | null>(null);
const activeFile = ref<customerFile>();

const show = (fileData: customerFile) => {
    activeFile.value = fileData;
    fileDetailsModal.value?.show();
};

const fileSize = computed(() => {
    if (activeFile.value?.file_upload.file_size === -1) {
        return "unknown";
    }

    return prettyBytes(activeFile.value?.file_upload.file_size || 0);
});

const fileFor = computed(() => {
    if (activeFile.value?.equip_name) {
        return activeFile.value?.equip_name;
    }

    if (activeFile.value?.customer_site.length) {
        let siteList = "";
        activeFile.value.customer_site.forEach((site) => {
            console.log(site);
            siteList = siteList.concat(`${site.site_name} <br />`);
        });

        return siteList;
    }

    return "General File";
});

const tableHeaders = [
    {
        label: "Name",
        field: "name",
    },
    {
        label: "Uploaded By",
        field: "uploaded_by",
    },
    {
        label: "Uploaded On",
        field: "created_at",
    },
    {
        label: "File Name",
        field: "file_upload",
    },
    {
        label: "File Size",
        field: "file_upload",
    },
    {
        label: "File For",
        field: "",
    },
];

defineExpose({ show });
</script>
