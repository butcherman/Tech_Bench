<script setup lang="ts">
import Modal from "@/Components/_Base/Modal.vue";
import NewCustomerEquipmentForm from "@/Forms/Customer/NewCustomerEquipmentForm.vue";
import { availableEquipment } from "@/Composables/Equipment/EquipmentData.module";
import {
    currentSite,
    customer,
    siteList,
} from "@/Composables/Customer/CustomerData.module";
import { useTemplateRef } from "vue";

const modal = useTemplateRef("add-equipment-modal");

const show = () => {
    modal.value?.show();
};

defineExpose({ show });
</script>

<template>
    <Modal
        ref="add-equipment-modal"
        title="Add Customer Equipment"
        size="large"
    >
        <NewCustomerEquipmentForm
            v-if="modal?.isOpen"
            :available-equipment-list="availableEquipment"
            :customer="customer"
            :site-list="siteList ?? []"
            :current-site="currentSite"
            @success="modal?.hide"
        />
    </Modal>
</template>
