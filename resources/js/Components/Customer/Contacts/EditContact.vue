<template>
    <EditButton class="btn-sm w-25 mx-2" @click="openEdit" />
    <Modal ref="editContactModal" title="Edit Contact">
        <ContactForm
            :contact-data="contact"
            @success="editContactModal?.hide()"
        />
    </Modal>
</template>

<script setup lang="ts">
import Modal from "@/Components/Base/Modal/Modal.vue";
import EditButton from '@/Components/Base/Buttons/EditButton.vue';
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
