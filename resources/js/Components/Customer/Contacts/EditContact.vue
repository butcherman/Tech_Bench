<template>
    <button class="btn btn-warning btn-sm mx-2 w-25" @click="openEdit">
        <fa-icon icon="edit" />
        Edit
    </button>
    <Modal ref="editContactModal" title="Edit Contact">
        <ContactForm
            :contact-data="contact"
            @success="editContactModal?.hide()"
        />
    </Modal>
</template>

<script setup lang="ts">
import Modal from "@/Components/Base/Modal/Modal.vue";
import ContactForm from "@/Forms/Customer/ContactForm.vue";
import { ref } from "vue";
import type { customerContactType } from "@/Types";

const emit = defineEmits(["openEdit"]);
defineProps<{
    contact?: customerContactType;
}>();

const editContactModal = ref<InstanceType<typeof Modal> | null>(null);

const openEdit = () => {
    editContactModal.value?.show();
    emit("openEdit");
};
</script>
