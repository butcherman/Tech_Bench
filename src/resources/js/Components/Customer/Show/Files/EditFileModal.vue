<script setup lang="ts">
import CustomerFileEditForm from "@/Forms/Customer/CustomerFileEditForm.vue";
import Modal from "@/Components/_Base/Modal.vue";
import { ref, useTemplateRef } from "vue";
import {
    customer,
    equipmentList,
    fileTypes,
    siteList,
} from "@/Composables/Customer/CustomerData.module";

const modal = useTemplateRef("edit-file-modal");

const activeFile = ref<customerFile>();

const show = (editFile: customerFile): void => {
    activeFile.value = editFile;
    modal.value?.show();
};

const hide = (): void => {
    activeFile.value = undefined;
    modal.value?.hide();
};

defineExpose({ show });
</script>

<template>
    <Modal
        ref="edit-file-modal"
        title="Edit File Data"
        size="large"
        @hidden="activeFile = undefined"
    >
        <CustomerFileEditForm
            v-if="activeFile"
            :customer="customer"
            :customer-file="activeFile"
            :site-list="siteList ?? []"
            :equip-list="equipmentList"
            :file-types="fileTypes"
            @success="hide"
        />
    </Modal>
</template>
