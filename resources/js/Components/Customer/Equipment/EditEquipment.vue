<template>
    <EditButton
        v-if="permission?.equipment.update"
        class="btn-sm"
        @click="editEquipmentModal?.show"
    />
    <Modal
        ref="editEquipmentModal"
        :title="`Edit ${equipData.name}`"
        @hidden="editEquipmentForm?.reset"
    >
        <EditEquipmentForm
            :equip-data="equipData"
            @success="editEquipmentModal?.hide()"
        />
    </Modal>
</template>

<script setup lang="ts">
import Modal from "@/Components/Base/Modal/Modal.vue";
import EditEquipmentForm from "@/Forms/Customer/EditEquipmentForm.vue";
import EditButton from "@/Components/Base/Buttons/EditButton.vue";
import { ref, inject } from "vue";
import { custPermissionsKey } from "@/SymbolKeys/CustomerKeys";

defineProps<{
    equipData: customerEquipment;
}>();
const permission = inject(custPermissionsKey) as customerPermissions;

const editEquipmentModal = ref<InstanceType<typeof Modal> | null>(null);
const editEquipmentForm = ref<InstanceType<typeof EditEquipmentForm> | null>(
    null
);
</script>
