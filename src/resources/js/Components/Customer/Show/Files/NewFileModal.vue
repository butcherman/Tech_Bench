<script setup lang="ts">
import CustomerFileAddForm from "@/Forms/Customer/CustomerFileAddForm.vue";
import Modal from "@/Components/_Base/Modal.vue";
import { router } from "@inertiajs/vue3";
import { useTemplateRef } from "vue";
import {
    customer,
    equipmentList,
    fileTypes,
    siteList,
} from "@/Composables/Customer/CustomerData.module";

const emit = defineEmits<{
    refreshStart: [];
    refreshEnd: [];
}>();

defineProps<{
    equipment?: customerEquipment;
}>();

const modal = useTemplateRef("add-file-modal");

const show = () => {
    modal.value?.show();
};

const onUploadSuccess = () => {
    emit("refreshStart");
    modal.value?.hide();
    router.reload({ only: ["fileList"], onFinish: () => emit("refreshEnd") });
};

defineExpose({ show });
</script>

<template>
    <Modal
        ref="add-file-modal"
        title="Add Customer File"
        size="large"
        prevent-outside-click
    >
        <CustomerFileAddForm
            v-if="equipmentList && modal?.isOpen"
            :customer="customer"
            :site-list="siteList ?? []"
            :equip-list="equipmentList"
            :file-types="fileTypes"
            :equipment="equipment"
            @success="onUploadSuccess"
        />
    </Modal>
</template>
