<template>
    <span>
        <EditBadge @click="editFileModal?.show()" />
        <Modal ref="editFileModal" title="Edit File Details">
            <CustomerFileEditForm
                :customer="customer"
                :customer-file="customerFile"
                :site-list="siteList"
                :equip-list="equipmentList"
                :file-types="fileStore.getFileTypes()"
                @success="editFileModal?.hide()"
            />
        </Modal>
    </span>
</template>

<script setup lang="ts">
import EditBadge from "../_Base/Badges/EditBadge.vue";
import CustomerFileEditForm from "@/Forms/Customer/CustomerFileEditForm.vue";
import Modal from "../_Base/Modal.vue";
import { ref } from "vue";
import { customer, siteList, equipmentList } from "@/State/CustomerState";
import { useFileTypeStore } from "@/Store/FileTypeStore";

defineProps<{
    customerFile: customerFile;
}>();

const editFileModal = ref<InstanceType<typeof Modal> | null>(null);
const fileStore = useFileTypeStore();
</script>
