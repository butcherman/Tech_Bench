<script setup lang="ts">
import BaseBadge from "../_Base/Badges/BaseBadge.vue";
import DeleteBadge from "../_Base/Badges/DeleteBadge.vue";
import Modal from "../_Base/Modal.vue";
import prettyBytes from "pretty-bytes";
import ResourceList from "../_Base/ResourceList.vue";
import TableStacked from "../_Base/TableStacked.vue";
import { ref, useTemplateRef } from "vue";

interface activeFileView {
    file_name: string;
    file_size: string;
    uploaded: string;
    uploaded_by?: string | number | undefined;
    notes?: string;
}

defineEmits<{
    move: [fileLinkFile];
}>();

const props = defineProps<{
    link: fileLink;
    fileList: fileLinkFile[];
    timeline?: fileLinkTimeline[];
    isUpload?: boolean;
}>();

const modal = useTemplateRef("file-info-modal");
const activeFile = ref<activeFileView | undefined>();

/**
 * Build an object of file details, and display modal with those values.
 */
const showFileDetails = (file: fileLinkFile) => {
    let timelineEntry = props.timeline?.find(
        (tl) => tl.timeline_id === file.pivot.timeline_id
    );

    let active: activeFileView = {
        file_name: file.file_name,
        file_size: prettyBytes(file.file_size),
        uploaded: file.pivot.created_at,
    };

    if (file.pivot.upload) {
        active.uploaded_by = timelineEntry?.added_by;
        active.notes = timelineEntry?.notes?.toString() ?? "N/A";
    }

    activeFile.value = active;

    modal.value?.show();
};
</script>

<template>
    <div>
        <ResourceList :list="fileList" compact hover-row>
            <template #list-item="{ item }">
                <a :href="item.href" class="block w-full ps-3">
                    {{ item.file_name }}
                    ({{ prettyBytes(item.file_size) }})
                </a>
            </template>
            <template #actions="{ item }">
                <BaseBadge
                    v-if="isUpload && !item.pivot.moved"
                    icon="share-nodes"
                    v-tooltip.left="'Move File to Customer'"
                    @click="$emit('move', item)"
                />
                <BaseBadge
                    class="mx-1"
                    icon="circle-info"
                    v-tooltip.left="'File Information'"
                    @click="showFileDetails(item)"
                />
                <DeleteBadge
                    :href="
                        $route('links.files.destroy', [
                            link.link_id,
                            item.file_id,
                        ])
                    "
                    v-tooltip.left="'Remove File From Link'"
                    confirm
                />
            </template>
        </ResourceList>
        <Modal
            title="File Information"
            ref="file-info-modal"
            @hidden="activeFile = undefined"
        >
            <TableStacked :items="activeFile" />
        </Modal>
    </div>
</template>
