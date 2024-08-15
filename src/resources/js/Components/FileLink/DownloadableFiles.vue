<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <AddButton
                    text="Add File"
                    class="float-end"
                    small
                    pill
                    @click="addFileModal?.show()"
                />
                Public Downloadable Files
            </div>
            <Table
                :columns="fileColumns"
                :rows="fileList"
                no-results-text="No Downloadable Files"
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
import AddButton from "../_Base/Buttons/AddButton.vue";
import DeleteBadge from "../_Base/Badges/DeleteBadge.vue";
import Modal from "../_Base/Modal.vue";
import FileLinkFileForm from "@/Forms/FileLink/FileLinkFileForm.vue";
import verifyModal from "@/Modules/verifyModal";
import prettyBytes from "pretty-bytes";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";

defineProps<{
    link: fileLink;
    fileList: fileLinkUpload[];
}>();

const addFileModal = ref<InstanceType<typeof Modal> | null>(null);
const showModal = ref(false);

const handleFileAdded = () => {
    router.reload();
    addFileModal.value?.hide();
};

const deleteFile = (file: fileLinkUpload) => {
    verifyModal("Do you want to delete this file?").then((res) => {
        if (res) {
            console.log(file.pivot);
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
</script>
