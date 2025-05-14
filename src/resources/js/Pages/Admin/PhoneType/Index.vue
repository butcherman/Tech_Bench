<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import EditBadge from "@/Components/_Base/Badges/EditBadge.vue";
import Modal from "@/Components/_Base/Modal.vue";
import PhoneTypeForm from "@/Forms/Admin/Misc/PhoneTypeForm.vue";
import ResourceList from "@/Components/_Base/ResourceList.vue";
import { ref, useTemplateRef } from "vue";

type adminPhoneType = {
    phone_type_id: number;
} & phoneType;

defineProps<{
    phoneTypes: adminPhoneType[];
}>();

const modal = useTemplateRef("phone-type-modal");
const activeType = ref<adminPhoneType | undefined>();

/**
 * Edit an existing phone type
 */
const editType = (type: adminPhoneType): void => {
    activeType.value = type;
    modal.value?.show();
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="flex justify-center">
        <Card class="tb-card" title="Contact Phone Types">
            <template #append-title>
                <AddButton
                    text="Add Phone Type"
                    size="small"
                    pill
                    @click="modal?.show"
                />
            </template>
            <ResourceList :list="phoneTypes" label-field="description">
                <template #actions="{ item }">
                    <EditBadge
                        v-tooltip="'Edit Phone Type'"
                        @click="editType(item)"
                    />
                    <DeleteBadge
                        class="ms-1"
                        :href="
                            $route(
                                'admin.phone-types.destroy',
                                item.phone_type_id
                            )
                        "
                        confirm
                        delete-method
                    />
                </template>
            </ResourceList>
        </Card>
        <Modal ref="phone-type-modal" @hidden="activeType = undefined">
            <PhoneTypeForm
                v-if="modal?.isOpen || activeType"
                :phone-type="activeType"
                @success="modal?.hide"
            />
        </Modal>
    </div>
</template>
