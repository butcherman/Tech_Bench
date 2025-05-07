<script setup lang="ts">
import CustomerContactForm from "@/Forms/Customer/CustomerContactForm.vue";
import Modal from "@/Components/_Base/Modal.vue";
import { computed, ref, useTemplateRef } from "vue";
import {
    customer,
    siteList,
    phoneTypes,
} from "@/Composables/Customer/CustomerData.module";

const modal = useTemplateRef("form-modal");
const activeContact = ref();
const modalTitle = computed(() =>
    activeContact.value ? "Edit Contact" : "Create Contact"
);

const show = (showContact: customerContact | null = null) => {
    if (showContact) {
        activeContact.value = showContact;
    }

    modal.value?.show();
};

defineExpose({ show });
</script>

<template>
    <Modal
        ref="form-modal"
        size="medium"
        :title="modalTitle"
        @hidden="activeContact = undefined"
    >
        <CustomerContactForm
            v-if="modal?.isOpen || activeContact"
            :customer="customer"
            :site-list="siteList ?? []"
            :phone-types="phoneTypes"
            :contact="activeContact"
            @success="modal?.hide()"
        />
    </Modal>
</template>
