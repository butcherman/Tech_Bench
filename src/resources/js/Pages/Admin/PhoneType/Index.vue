<template>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        Contact Phone Types
                        <AddButton
                            class="float-end"
                            text="Add Phone Type"
                            pill
                            small
                            @click="addPhoneType"
                        />
                    </div>
                    <Table :columns="cols" :rows="phoneTypes">
                        <template #column="{ rowData }">
                            <fa-icon :icon="rowData.icon_class" />
                            {{ rowData.description }}
                        </template>
                        <template #action="{ rowData }">
                            <DeleteBadge
                                class="float-end"
                                @click="deletePhoneType(rowData)"
                            />
                            <EditBadge
                                class="float-end"
                                @click="editPhoneType(rowData)"
                            />
                        </template>
                    </Table>
                </div>
            </div>
        </div>
        <Modal
            ref="phoneTypeModal"
            @show="showModal = true"
            @hidden="clearModal"
        >
            <PhoneTypeForm
                v-if="showModal"
                :phone-type="activeType"
                @success="phoneTypeModal?.hide()"
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
import PhoneTypeForm from "@/Forms/Admin/PhoneTypeForm.vue";
import verifyModal from "@/Modules/verifyModal";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";

type adminPhoneType = {
    phone_type_id: number;
} & phoneType;

defineProps<{
    phoneTypes: adminPhoneType[];
}>();

const phoneTypeModal = ref<InstanceType<typeof Modal> | null>(null);
const showModal = ref(false);
const activeType = ref<adminPhoneType | undefined>();

const cols = [
    {
        label: "Description",
        field: "description",
    },
];

const clearModal = () => {
    activeType.value = undefined;
    showModal.value = false;
};

const editPhoneType = (phoneType: adminPhoneType) => {
    activeType.value = phoneType;
    phoneTypeModal.value?.show();
};

const deletePhoneType = (phoneType: adminPhoneType) => {
    verifyModal("This cannot be undone").then((res) => {
        if (res) {
            router.delete(
                route("admin.phone-types.destroy", phoneType.phone_type_id)
            );
        }
    });
};

const addPhoneType = () => {
    phoneTypeModal.value?.show();
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
