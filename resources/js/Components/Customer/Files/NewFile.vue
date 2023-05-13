<template>
    <AddButton
        class="float-end"
        pill
        small
        title="New File"
        v-tooltip
        @click="newFileModal.show()"
    />
    <Modal ref="newFileModal" title="New File">
        <NewFileForm />
    </Modal>
</template>

<script setup lang="ts">
import AddButton from "@/Components/Base/Buttons/AddButton.vue";
import Modal from "@/Components/Base/Modal/Modal.vue";
import NewFileForm from '@/Forms/Customer/NewFileForm.vue';
import { ref } from "vue";
import type { customerEquipmentType } from "@/Types";

const newFileModal = ref<InstanceType<typeof Modal> | null>(null);
</script>
