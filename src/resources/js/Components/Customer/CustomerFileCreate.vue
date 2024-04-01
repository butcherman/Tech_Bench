<template>
    <AddButton small pill @click="onShowModal">
        Add File
        <Modal ref="newFileModal" title="New Customer File">
            <CustomerFileForm
                v-if="isShown"
                :customer="customer"
                :site-list="siteList"
                :equip-list="equipmentList"
                :file-types="fileStore.getFileTypes()"
                @success="onSuccess"
            />
        </Modal>
    </AddButton>
</template>

<script setup lang="ts">
import AddButton from "../_Base/Buttons/AddButton.vue";
import Modal from "../_Base/Modal.vue";
import CustomerFileForm from "@/Forms/Customer/CustomerFileForm.vue";
import { ref, reactive, onMounted, nextTick } from "vue";
import { useFileTypeStore } from "@/Store/FileTypeStore";
import {
    customer,
    currentSite,
    siteList,
    equipmentList,
} from "@/State/CustomerState";

// const props = defineProps<{}>();

const isShown = ref(false);
const fileStore = useFileTypeStore();
const newFileModal = ref<InstanceType<typeof Modal> | null>(null);

const onShowModal = () => {
    isShown.value = true;

    newFileModal.value?.show();
    // nextTick(() => );
};

const onSuccess = () => {
    console.log("success");

    newFileModal.value?.hide();
    isShown.value = false;
};
</script>
