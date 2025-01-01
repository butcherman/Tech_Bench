<template>
    <div>
        <Card title="Customer File Types" class="reasonable-width">
            <template #append-title>
                <AddButton
                    size="small"
                    text="New File Type"
                    pill
                    @click="editModal?.show()"
                />
            </template>
            <v-list>
                <v-list-item
                    v-for="type in fileTypes"
                    :key="type.file_type_id"
                    class="border-b"
                >
                    <span class="float-end">
                        <EditBadge @click="openEditModal(type)" />
                        <DeleteBadge @click="deleteFileType(type)" />
                    </span>
                    {{ type.description }}
                </v-list-item>
            </v-list>
        </Card>
        <Modal
            ref="editModal"
            :title="modalTitle"
            @hidden="activeType = undefined"
        >
            <FileTypeForm
                :file-type="activeType"
                @success="editModal?.hide()"
            />
        </Modal>
    </div>
</template>

<script setup lang="ts">
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import EditBadge from "@/Components/_Base/Badges/EditBadge.vue";
import Card from "@/Components/_Base/Card.vue";
import Modal from "@/Components/_Base/Modal.vue";
import FileTypeForm from "@/Forms/Admin/FileTypeForm.vue";
import AppLayout from "@/Layouts/App/AppLayout.vue";
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import VerifyModal from "@/Modules/VerifyModal";
import { computed, ref } from "vue";
import { router } from "@inertiajs/vue3";

defineProps<{
    fileTypes: customerFileType[];
}>();

const editModal = ref<InstanceType<typeof Modal> | null>(null);
const activeType = ref<customerFileType | undefined>(undefined);
const modalTitle = computed(() =>
    activeType.value ? "Edit File Type" : "Create File Type"
);

/**
 * Edit a file type
 */
const openEditModal = (type: customerFileType) => {
    activeType.value = type;
    editModal.value?.show();
};

/**
 * Remove a file type
 */
const deleteFileType = (fileType: customerFileType) => {
    VerifyModal().then((res) => {
        if (res) {
            router.delete(
                route("admin.file-types.destroy", fileType.file_type_id)
            );
        }
    });
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
