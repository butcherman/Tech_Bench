<template>
    <EditButton class="btn-sm" @click="editEquipmentModal?.show" />
    <Modal ref="editEquipmentModal" :title="`Edit ${equipData.name}`">
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
import type { customerEquipmentType, customerPermissionType } from "@/Types";

defineProps<{
    equipData: customerEquipmentType;
}>();
const permission = inject(custPermissionsKey) as customerPermissionType;

const editEquipmentModal = ref<InstanceType<typeof Modal> | null>(null);
</script>
