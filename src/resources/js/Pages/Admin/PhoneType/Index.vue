<template>
    <div>
        <Card title="Contact Phone Types" class="tb-card">
            <template #append-title>
                <AddButton
                    text="Add Phone Type"
                    size="small"
                    pill
                    @click="addPhoneType"
                />
            </template>
            <ResourceList :list="phoneTypes" label-field="description">
                <template #list-item="{ item }">
                    <fa-icon :icon="item.icon_class" />
                    {{ item.description }}
                </template>
                <template #actions="{ item }">
                    <EditBadge
                        class="mx-1 pointer"
                        v-tooltip="'Edit File Type'"
                        @click="editPhoneType(item)"
                    />
                    <DeleteBadge
                        class="mx-1"
                        v-tooltip="'Delete File Type'"
                        confirm
                        @accepted="deletePhoneType(item)"
                    />
                </template>
            </ResourceList>
        </Card>
        <Modal ref="phoneTypeModal" @hidden="clearModal">
            <PhoneTypeForm
                v-if="modalShown"
                :phone-type="activeType"
                @success="phoneTypeModal?.hide()"
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
import Modal from "@/Components/_Base/Modal.vue";
import PhoneTypeForm from "@/Forms/Admin/PhoneTypeForm.vue";
import ResourceList from "@/Components/_Base/ResourceList.vue";
import { ref, useTemplateRef } from "vue";
import { router } from "@inertiajs/vue3";

type adminPhoneType = {
    phone_type_id: number;
} & phoneType;

defineProps<{
    phoneTypes: adminPhoneType[];
}>();

const phoneTypeModal = useTemplateRef("phoneTypeModal");
const modalShown = ref<boolean>(false);
const activeType = ref<adminPhoneType | undefined>(undefined);

/**
 * Show Phone Type Form
 */
const addPhoneType = (): void => {
    modalShown.value = true;
    phoneTypeModal.value?.show();
};

/**
 * Show Edit Phone Type Form
 */
const editPhoneType = (type: adminPhoneType): void => {
    activeType.value = type;
    modalShown.value = true;
    phoneTypeModal.value?.show();
};

/**
 * Delete Phone Type after confirmation
 */
const deletePhoneType = (type: adminPhoneType): void => {
    router.delete(route("admin.phone-types.destroy", type.phone_type_id));
};

/**
 * Reset Phone Type Form
 */
const clearModal = (): void => {
    activeType.value = undefined;
    modalShown.value = false;
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
