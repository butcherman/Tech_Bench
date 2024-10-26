<template>
    <AddButton small pill @click="onShowModal">
        Add File
        <Modal
            ref="newFileModal"
            size="xl"
            title="New Customer File"
            @hide-prevented="onPreventClosing"
            @hidden="isShown = false"
        >
            <CustomerFileCreateForm
                v-if="isShown"
                :customer="customer"
                :site-list="siteList"
                :equip-list="equipmentList"
                :file-types="fileStore.getFileTypes()"
                :current-site="currentSite"
                :equipment="equipment"
                @submitting="onSubmit"
                @success="onSuccess"
            />
        </Modal>
    </AddButton>
</template>

<script setup lang="ts">
import AddButton from "../_Base/Buttons/AddButton.vue";
import Modal from "../_Base/Modal.vue";
import CustomerFileCreateForm from "@/Forms/Customer/CustomerFileCreateForm.vue";
import okModal from "@/Modules/okModal";
import { ref } from "vue";
import { useFileTypeStore } from "@/Store/FileTypeStore";
import { router } from "@inertiajs/vue3";
import {
    customer,
    currentSite,
    siteList,
    equipmentList,
    toggleLoading,
} from "@/State/CustomerState";

defineProps<{
    equipment?: customerEquipment;
}>();

const isShown = ref(false);
const fileStore = useFileTypeStore();
const newFileModal = ref<InstanceType<typeof Modal> | null>(null);

const onShowModal = () => {
    isShown.value = true;
    newFileModal.value?.show();
};

const onSubmit = () => {
    newFileModal.value?.stopClose();
    toggleLoading("files");
};

const onSuccess = () => {
    isShown.value = false;
    newFileModal.value?.enableClose();
    newFileModal.value?.hide();
    router.reload({
        only: ["flash", "files"],
        onFinish: () => toggleLoading("files"),
    });
};

const onPreventClosing = () => {
    okModal(
        "File Upload in progress.  Please cancel upload before navigating away"
    );
};
</script>
