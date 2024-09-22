<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <AddButton
                    v-if="public && !isAdmin"
                    text="Add File"
                    class="float-end"
                    small
                    pill
                    @click="addFileModal?.show()"
                />
                <AlertButton
                    v-if="!public && !upToDate"
                    text-variant="warning"
                    title="Refresh to see new file uploaded"
                />
                <RefreshButton
                    v-if="!public"
                    :only="['timeline', 'uploaded-files']"
                    @loading-complete="$emit('refreshed')"
                />
                {{ title }}:
            </div>
            <Table
                :columns="public ? publicFileColumns : fileColumns"
                :rows="fileList"
                :no-results-text="noResultsText"
                striped
                responsive
                no-inertia-link
            >
                <template #action="{ rowData }">
                    <DeleteBadge
                        title="Delete File"
                        @click="deleteFile(rowData)"
                    />
                </template>
                <template #column="{ columnName, rowData }">
                    <span v-if="columnName === 'file_size'">
                        {{ prettyBytes(rowData.file_size) }}
                    </span>
                </template>
            </Table>
        </div>
        <Modal
            ref="addFileModal"
            title="Add File"
            @shown="showModal = true"
            @hidden="showModal = false"
        >
            <FileLinkFileForm
                v-if="showModal"
                :link-id="link.link_id"
                @success="handleFileAdded"
            />
        </Modal>
    </div>
</template>

<script setup lang="ts">
import Table from "../_Base/Table.vue";
import RefreshButton from "../_Base/Buttons/RefreshButton.vue";
import AddButton from "../_Base/Buttons/AddButton.vue";
import AlertButton from "@/Components/_Base/Buttons/AlertButton.vue";
import DeleteBadge from "../_Base/Badges/DeleteBadge.vue";
import Modal from "../_Base/Modal.vue";
import FileLinkFileForm from "@/Forms/FileLink/FileLinkFileForm.vue";
import verifyModal from "@/Modules/verifyModal";
import prettyBytes from "pretty-bytes";
import { ref, computed } from "vue";
import { router } from "@inertiajs/vue3";

defineEmits(["refreshed"]);
const props = defineProps<{
    link: fileLink;
    fileList: fileLinkUpload[];
    public?: boolean;
    isAdmin?: boolean;
    upToDate?: boolean;
}>();

const addFileModal = ref<InstanceType<typeof Modal> | null>(null);
const showModal = ref(false);
const title = computed(() =>
    props.public ? "Public Downloadable Files" : "Uploaded Files"
);
const noResultsText = computed(() =>
    props.public ? "No Downloadable Files" : "No Uploaded Files"
);

const handleFileAdded = () => {
    router.reload();
    addFileModal.value?.hide();
};

const deleteFile = (file: fileLinkUpload) => {
    verifyModal("Do you want to delete this file?").then((res) => {
        if (res) {
            router.delete(
                route("links.destroy-file", [
                    file.pivot.link_id,
                    file.pivot.link_file_id,
                ])
            );
        }
    });
};

const fileColumns = [
    {
        label: "File Name",
        field: "file_name",
        sort: true,
    },
    {
        label: "Date Added",
        field: "created_at",
        sort: true,
        sort_field: "created_stamp",
    },
    {
        label: "Size",
        field: "file_size",
        sort: true,
    },
];

const publicFileColumns = [
    {
        label: "File Name",
        field: "file_name",
        sort: true,
    },
    {
        label: "Date Added",
        field: "created_at",
        sort: true,
        sort_field: "created_stamp",
    },
    {
        label: "Size",
        field: "file_size",
        sort: true,
    },
];
</script>
