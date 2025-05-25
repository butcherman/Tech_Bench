<script setup lang="ts">
import AddFileForm from "@/Forms/FileLink/AddFileForm.vue";
import Modal from "../_Base/Modal.vue";
import { router } from "@inertiajs/vue3";
import { useTemplateRef } from "vue";

defineProps<{
    link: fileLink;
}>();

const modal = useTemplateRef("add-modal");

/**
 * Show the form
 */
const show = () => {
    modal.value?.show();
};

/**
 * Close the Modal and reload the page
 */
const onSuccess = () => {
    modal.value?.hide();
    router.reload({ only: ["timeline", "downloads"] });
};

defineExpose({ show });
</script>

<template>
    <Modal ref="add-modal">
        <AddFileForm :link="link" @success="onSuccess" />
    </Modal>
</template>
