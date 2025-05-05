<script setup lang="ts">
import Modal from "@/Components/_Base/Modal.vue";
import prettyBytes from "pretty-bytes";
import TableStacked from "@/Components/_Base/TableStacked.vue";
import { computed, ref, useTemplateRef } from "vue";

const detailsModal = useTemplateRef("details-modal");

const activeFile = ref<customerFile>();

/**
 * Re-work file data to only show necessary fields.
 */
const computedFileData = computed(() => {
    if (!activeFile.value) {
        return undefined;
    }

    return {
        name: activeFile.value.name,
        uploaded_by: activeFile.value.uploaded_by,
        uploaded_on: activeFile.value.created_at,
        file_name: activeFile.value.file_upload.file_name,
        file_size: prettyBytes(activeFile.value.file_upload.file_size),
        file_type: activeFile.value.file_type,
        file_category: activeFile.value.file_category,
    };
});

/**
 * Open Modal to show the file details.
 */
const show = (showFile: customerFile): void => {
    activeFile.value = showFile;
    detailsModal.value?.show();
};

defineExpose({ show });
</script>

<template>
    <Modal
        ref="details-modal"
        title="File Details"
        @hidden="activeFile = undefined"
    >
        <TableStacked v-if="computedFileData" :items="computedFileData" />
    </Modal>
</template>
