<template>
    <Modal
        title="Edit Contact"
        ref="editContactModal"
        size="lg"
        @hidden="$emit('hidden')"
    >
        <CustomerContactForm
            v-if="isShown"
            :customer="customer"
            :site-list="siteList"
            :phone-types="phoneStore.getPhoneTypesString()"
            :contact="contact"
            @submitting="toggleLoading('contacts')"
            @success="$emit('success')"
        />
    </Modal>
</template>

<script setup lang="ts">
import Modal from "@/Components/_Base/Modal.vue";
import CustomerContactForm from "@/Forms/Customer/CustomerContactForm.vue";
import { ref } from "vue";
import { customer, siteList, toggleLoading } from "@/State/CustomerState";
import { usePhoneTypesStore } from "@/Store/PhoneTypesStore";

defineEmits(["success", "hidden"]);
defineProps<{
    contact: customerContact;
}>();

const isShown = ref(false);
const phoneStore = usePhoneTypesStore();
const editContactModal = ref<InstanceType<typeof Modal> | null>(null);

const openModal = () => {
    isShown.value = true;
    editContactModal.value?.show();
};

const closeModal = () => {
    isShown.value = false;
    editContactModal.value?.hide();
    toggleLoading("contacts");
};

defineExpose({ openModal, closeModal });
</script>
