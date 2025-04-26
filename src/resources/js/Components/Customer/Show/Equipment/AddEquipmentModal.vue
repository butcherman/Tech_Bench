<script setup lang="ts">
import Modal from "@/Components/_Base/Modal.vue";
import NewCustomerEquipmentForm from "@/Forms/Customer/NewCustomerEquipmentForm.vue";
import { availableEquipment } from "@/Composables/Equipment/EquipmentData.module";
import { customer, siteList } from "@/Composables/Customer/CustomerData.module";
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
        Add Equipment Form
        <!-- <NewCustomerEquipmentForm
            v-if="availableEquipment"
            :available-equipment-list="availableEquipment"
            :customer="customer"
            :site-list="siteList"
        /> -->
    </Modal>
</template>
