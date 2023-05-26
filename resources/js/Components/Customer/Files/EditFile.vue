<template>
    <EditBadge @click="editFileModal?.show" />
    <Modal ref="editFileModal" title="Edit File">
        <EditFileForm :file="file" @success="editFileModal?.hide" />
    </Modal>
</template>

<script setup lang="ts">
import EditBadge from "@/Components/Base/Buttons/EditBadge.vue";
import Modal from "@/Components/Base/Modal/Modal.vue";
import EditFileForm from "@/Forms/Customer/EditFileForm.vue";
import { ref } from "vue";

defineProps<{
    file: customerFile;
}>();

const editFileModal = ref<InstanceType<typeof Modal> | null>(null);
</script>
