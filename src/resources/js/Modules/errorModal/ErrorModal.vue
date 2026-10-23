<script setup lang="ts">
import ErrorMessage from "@/Components/_Base/ErrorMessage.vue";
import Modal from "@/Components/_Base/Modal.vue";
import { onMounted, useTemplateRef } from "vue";

defineProps<{
    status?: number;
    message: string;
}>();

const modal = useTemplateRef("error-modal");

onMounted(() => modal.value?.show());
</script>

<template>
    <Modal ref="error-modal">
        <ErrorMessage class="text-center" :status="status" :message="message" />
    </Modal>
</template>
