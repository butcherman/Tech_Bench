<template>
    <AddButton pill small @click="addEquipmentModal?.show">
        Add Equipment
        <Modal
            ref="addEquipmentModal"
            title="Add Customer Equipment"
            @show="modalActive = true"
            @hidden="modalActive = false"
        >
            <CustomerEquipmentForm
                v-if="modalActive"
                :equipment-list="equipStore.getSelectBox()"
                :customer="customer"
                :site-list="siteList"
                @submitting="onSubmit"
                @success="onSuccess"
                @has-errors="onSubmit"
            />
        </Modal>
    </AddButton>
</template>

<script setup lang="ts">
import CustomerEquipmentForm from "@/Forms/Customer/CustomerEquipmentForm.vue";
import AddButton from "../_Base/Buttons/AddButton.vue";
import Modal from "../_Base/Modal.vue";
import { ref } from "vue";
import { customer, siteList, toggleLoading } from "@/State/CustomerState";
import { useEquipmentStore } from "@/Store/EquipmentStore";

const modalActive = ref<boolean>(false);
const addEquipmentModal = ref<InstanceType<typeof Modal> | null>(null);

const onSubmit = () => {
    toggleLoading("equipment");
};
const onSuccess = () => {
    addEquipmentModal.value?.hide();
    toggleLoading("equipment");
};

const equipStore = useEquipmentStore();
</script>
