<template>
    <div>
        <Card title="CustomerFile Types" class="tb-card">
            <template #append-title>
                <AddButton
                    text="Add File Type"
                    size="small"
                    pill
                    @click="addFileType"
                />
            </template>
            <ResourceList :list="fileTypes" label-field="description">
                <template #actions="{ item }">
                    <EditBadge
                        class="mx-1 pointer"
                        v-tooltip="'Edit File Type'"
                        @click="editFileType(item)"
                    />
                    <DeleteBadge
                        class="mx-1"
                        v-tooltip="'Delete File Type'"
                        confirm
                        @accepted="deleteFileType(item)"
                    />
                </template>
            </ResourceList>
        </Card>
        <Modal ref="fileTypeModal" @hidden="clearModal">
            <FileTypeForm
                v-if="modalShown"
                :file-type="activeType"
                @success="fileTypeModal?.hide()"
            />
        </Modal>
    </div>
</template>

<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import EditBadge from "@/Components/_Base/Badges/EditBadge.vue";
import FileTypeForm from "@/Forms/Admin/FileTypeForm.vue";
import Modal from "@/Components/_Base/Modal.vue";
import ResourceList from "@/Components/_Base/ResourceList.vue";
import { router } from "@inertiajs/vue3";
import { ref, useTemplateRef } from "vue";

defineProps<{
    fileTypes: customerFileType[];
}>();

const fileTypeModal = useTemplateRef("fileTypeModal");
const modalShown = ref<boolean>(false);
const activeType = ref<customerFileType | undefined>(undefined);

/**
 * Show New File Type Form
 */
const addFileType = (): void => {
    modalShown.value = true;
    fileTypeModal.value?.show();
};

/**
 * Show Edit File Type Form
 */
const editFileType = (type: customerFileType): void => {
    activeType.value = type;
    modalShown.value = true;
    fileTypeModal.value?.show();
};

/**
 * Destroy File Type after confirmation
 */
const deleteFileType = (type: customerFileType): void => {
    router.delete(route("admin.file-types.destroy", type.file_type_id));
};

/**
 * Reset File Type Form
 */
const clearModal = (): void => {
    activeType.value = undefined;
    modalShown.value = false;
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
