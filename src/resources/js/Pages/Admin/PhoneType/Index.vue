<template>
    <div>
        <Card title="Contact Phone Types" class="tb-card">
            <template #append-title>
                <AddButton
                    size="small"
                    text="New Phone Type"
                    pill
                    @click="editModal?.show()"
                />
            </template>
            <v-list>
                <v-list-item
                    v-for="type in phoneTypes"
                    :key="type.phone_type_id"
                    class="border-b"
                >
                    <span class="float-end">
                        <EditBadge @click="openEditModal(type)" />
                        <DeleteBadge @click="deletePhoneType(type)" />
                    </span>
                    <v-icon
                        size="x-small"
                        :icon="type.icon_class"
                        class="w-auto"
                    />
                    {{ type.description }}
                </v-list-item>
            </v-list>
        </Card>
        <Modal
            ref="editModal"
            :title="modalTitle"
            @hidden="activeType = undefined"
        >
            <PhoneTypeForm
                :phone-type="activeType"
                @success="editModal?.hide()"
            />
        </Modal>
    </div>
</template>

<script setup lang="ts">
import Card from "@/Components/_Base/Card.vue";
import AppLayout from "@/Layouts/App/AppLayout.vue";
import EditBadge from "@/Components/_Base/Badges/EditBadge.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import Modal from "@/Components/_Base/Modal.vue";
import PhoneTypeForm from "@/Forms/Admin/PhoneTypeForm.vue";
import VerifyModal from "@/Modules/VerifyModal";
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import { computed, ref } from "vue";
import { router } from "@inertiajs/vue3";

defineProps<{
    phoneTypes: phoneType[];
}>();

const editModal = ref<InstanceType<typeof Modal> | null>(null);
const activeType = ref<phoneType | undefined>(undefined);
const modalTitle = computed(() =>
    activeType.value ? "Edit Phone Type" : "Create Phone Type"
);

/**
 * Edit a phone type
 */
const openEditModal = (type: phoneType) => {
    activeType.value = type;
    editModal.value?.show();
};

/**
 * Remove a phone type
 */
const deletePhoneType = (phoneType: phoneType) => {
    VerifyModal("This cannot be undone").then((res) => {
        if (res) {
            router.delete(
                route("admin.phone-types.destroy", phoneType.phone_type_id)
            );
        }
    });
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
