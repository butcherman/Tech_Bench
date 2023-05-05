<template>
    <button
        class="float-end btn btn-info btn-pill btn-sm"
        title="New Contact"
        v-tooltip
        @click="newContactModal?.show()"
    >
        <fa-icon icon="fa-plus" />
        Add
    </button>
    <Modal ref="newContactModal" title="New Contact" size="lg">
        <ContactForm @success="newContactModal?.hide()" />
    </Modal>
</template>

<script setup lang="ts">
import Modal from "@/Components/Base/Modal/Modal.vue";
import ContactForm from "@/Forms/Customer/ContactForm.vue";
import { ref } from "vue";

const newContactModal = ref<InstanceType<typeof Modal> | null>(null);
</script>
