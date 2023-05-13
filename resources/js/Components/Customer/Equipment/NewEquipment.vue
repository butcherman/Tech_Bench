<template>
    <AddButton
        class="float-end"
        pill
        small
        title="Add Equipment"
        v-tooltip
        @click="newEquipmentModal.show()"
    />
    <Modal ref="newEquipmentModal" title="New Equipment">
        <NewCustomerEquipmentForm
            :existing-equipment="existingEquipment"
            @success="newEquipmentModal?.hide()"
        />
    </Modal>
</template>

<script setup lang="ts">
import AddButton from "@/Components/Base/Buttons/AddButton.vue";
import Modal from "@/Components/Base/Modal/Modal.vue";
import NewCustomerEquipmentForm from "@/Forms/Customer/NewCustomerEquipmentForm.vue";
import { ref } from "vue";
import type { customerEquipmentType } from "@/Types";

defineProps<{
    existingEquipment: customerEquipmentType[];
}>();

const newEquipmentModal = ref<InstanceType<typeof Modal> | null>(null);
</script>
