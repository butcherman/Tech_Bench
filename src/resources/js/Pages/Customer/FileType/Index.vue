<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import CustomerFileTypeForm from "@/Forms/Customer/CustomerFileTypeForm.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import EditBadge from "@/Components/_Base/Badges/EditBadge.vue";
import Modal from "@/Components/_Base/Modal.vue";
import ResourceList from "@/Components/_Base/ResourceList.vue";
import { ref, useTemplateRef } from "vue";

defineProps<{
    fileTypes: customerFileType[];
}>();

const modal = useTemplateRef("file-type-modal");
const activeType = ref<customerFileType | undefined>();

/**
 * Edit the name of an existing file type
 */
const editType = (type: customerFileType): void => {
    activeType.value = type;
    modal.value?.show();
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="flex justify-center">
        <Card title="Customer File Types" class="tb-card">
            <template #append-title>
                <AddButton
                    text="Add File Type"
                    size="small"
                    pill
                    @click="modal?.show"
                />
            </template>
            <ResourceList :list="fileTypes" label-field="description">
                <template #actions="{ item }">
                    <EditBadge
                        v-tooltip="'Edit File Type Name'"
                        @click="editType(item)"
                    />
                    <DeleteBadge
                        class="ms-1"
                        :href="
                            $route(
                                'admin.file-types.destroy',
                                item.file_type_id
                            )
                        "
                        confirm
                        delete-method
                    />
                </template>
            </ResourceList>
        </Card>
        <Modal ref="file-type-modal" @hidden="activeType = undefined">
            <CustomerFileTypeForm
                v-if="modal?.isOpen || activeType"
                :file-type="activeType"
                @success="modal?.hide"
            />
        </Modal>
    </div>
</template>
