<template>
    <AddButton
        class="float-end"
        pill
        small
        title="New File"
        v-tooltip
        @click="newFileModal?.show()"
    />
    <Modal
        ref="newFileModal"
        title="New File"
        @hidePrevented="preventClosing"
        @hidden="newFileForm?.resetForm"
    >
        <NewFileForm
            ref="newFileForm"
            @submitting="newFileModal?.stopClose"
            @completed="uploadCompleted"
            @canceled="uploadCanceled"
        />
    </Modal>
</template>

<script setup lang="ts">
import AddButton from "@/Components/Base/Buttons/AddButton.vue";
import Modal from "@/Components/Base/Modal/Modal.vue";
import NewFileForm from "@/Forms/Customer/NewFileForm.vue";
import { ref } from "vue";
import { okModal } from "@/Modules/okModal.module";

const newFileModal = ref<InstanceType<typeof Modal> | null>(null);
const newFileForm = ref<InstanceType<typeof NewFileForm> | null>(null);

const preventClosing = () => {
    okModal(
        "File Upload in progress.  Please cancel upload before navigating away",
        {
            title: "ERROR",
        }
    );
};

const uploadCompleted = () => {
    newFileModal.value?.enableClose();
    newFileModal.value?.hide();
};

const uploadCanceled = () => {
    newFileModal.value?.enableClose();
};
</script>
