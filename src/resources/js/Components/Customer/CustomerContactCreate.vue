<template>
    <AddButton
        pill
        small
        title="Create New Contact"
        v-tooltip
        @click="showModal"
    >
        Add Contact
        <Modal ref="newContactModal" title="New Customer Contact" size="lg">
            <CustomerContactForm
                v-if="isShown"
                :customer="customer"
                :cust-id="customer.cust_id"
                :site-list="siteList"
                :phone-types="phoneStore.getPhoneTypesString()"
                @success="hideModal"
            />
        </Modal>
    </AddButton>
</template>

<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import Modal from "../_Base/Modal.vue";
import CustomerContactForm from "@/Forms/Customer/CustomerContactForm.vue";
import { ref } from "vue";
import { customer, siteList } from "@/State/CustomerState";
import { usePhoneTypesStore } from "@/Store/PhoneTypesStore";

const newContactModal = ref<InstanceType<typeof Modal> | null>(null);
const phoneStore = usePhoneTypesStore();

const isShown = ref(false);

const showModal = () => {
    isShown.value = true;
    newContactModal.value?.show();
};

const hideModal = () => {
    isShown.value = false;
    newContactModal.value?.hide();
};
</script>
