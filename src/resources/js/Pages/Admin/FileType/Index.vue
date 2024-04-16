<template>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        Customer File Types
                        <AddButton
                            class="float-end"
                            text="Add File Type"
                            pill
                            small
                            @click="addFileType"
                        />
                    </div>
                    <Table :columns="cols" :rows="fileTypes">
                        <template #action="{ rowData }">
                            <DeleteBadge
                                class="float-end"
                                @click="deleteFileType(rowData)"
                            />
                            <EditBadge
                                class="float-end"
                                @click="editFileType(rowData)"
                            />
                        </template>
                    </Table>
                </div>
            </div>
        </div>
        <Modal
            ref="fileTypeModal"
            @show="showModal = true"
            @hidden="clearModal"
        >
            <FileTypeForm
                v-if="showModal"
                :file-type="activeType"
                @success="fileTypeModal?.hide()"
            />
        </Modal>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import Table from "@/Components/_Base/Table.vue";
import EditBadge from "@/Components/_Base/Badges/EditBadge.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import Modal from "@/Components/_Base/Modal.vue";
import FileTypeForm from "@/Forms/Admin/FileTypeForm.vue";
import { ref } from "vue";
import verifyModal from "@/Modules/verifyModal";
import { router } from "@inertiajs/vue3";

defineProps<{
    fileTypes: customerFileType[];
}>();

const fileTypeModal = ref<InstanceType<typeof Modal> | null>(null);
const showModal = ref(false);
const activeType = ref();

const cols = [
    {
        label: "Description",
        field: "description",
    },
];

const clearModal = () => {
    activeType.value = null;
    showModal.value = false;
};

const editFileType = (fileType: customerFileType) => {
    console.log("edit");
    activeType.value = fileType;
    fileTypeModal.value?.show();
};

const deleteFileType = (fileType: customerFileType) => {
    console.log("delete");
    verifyModal().then((res) => {
        if (res) {
            router.delete(
                route("admin.file-types.destroy", fileType.file_type_id)
            );
        }
    });
};

const addFileType = () => {
    console.log("add");
    fileTypeModal.value?.show();
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
