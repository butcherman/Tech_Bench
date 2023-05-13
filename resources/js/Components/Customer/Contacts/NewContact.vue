<template>
    <AddButton
        class="float-end"
        pill
        small
        title="Add Contact"
        v-tooltip
        @click="newContactModal.show()"
    />
    <Modal ref="newContactModal" title="New Contact" size="lg">
        <ContactForm @success="newContactModal?.hide()" />
    </Modal>
</template>

<script setup lang="ts">
import Modal from "@/Components/Base/Modal/Modal.vue";
import AddButton from "@/Components/Base/Buttons/AddButton.vue";
import ContactForm from "@/Forms/Customer/ContactForm.vue";
import { ref } from "vue";

const newContactModal = ref<InstanceType<typeof Modal> | null>(null);
</script>
