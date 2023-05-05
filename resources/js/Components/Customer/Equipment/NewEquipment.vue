<template>
    <button
        class="float-end btn btn-info btn-pill btn-sm"
        title="New Equipment"
        v-tooltip
        @click="newEquipmentModal?.show()"
    >
        <fa-icon icon="fa-plus" />
        Add
    </button>
    <Modal ref="newEquipmentModal" title="New Equipment">
        <NewCustomerEquipmentForm
            :existing-equipment="existingEquipment"
            @success="newEquipmentModal?.hide()"
        />
    </Modal>
</template>

<script setup lang="ts">
import Modal from "@/Components/Base/Modal/Modal.vue";
import NewCustomerEquipmentForm from "@/Forms/Customer/NewCustomerEquipmentForm.vue";
import { ref } from "vue";
import type { customerEquipmentType } from "@/Types";

defineProps<{
    existingEquipment: customerEquipmentType[];
}>();

const newEquipmentModal = ref<InstanceType<typeof Modal> | null>(null);
</script>
