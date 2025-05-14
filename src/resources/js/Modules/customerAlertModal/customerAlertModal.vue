<script setup lang="ts">
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import Modal from "@/Components/_Base/Modal.vue";
import { onMounted, useTemplateRef } from "vue";

const emit = defineEmits<{
    reload: [];
}>();

defineProps<{
    updatedRoute: string;
}>();

const modal = useTemplateRef("customer-alert-modal");

const onReloadClicked = () => {
    emit("reload");
    modal.value?.hide();
};

onMounted(() => modal.value?.show());
</script>

<template>
    <Modal ref="customer-alert-modal" prevent-outside-click>
        <h1 class="text-center text-danger">Customer Link Changed</h1>
        <h3 class="text-center">
            Customer Name has been updated. As a side effect, the link to view
            this customer is no longer valid. Please use the new link below to
            reload the page with the new customer link.
        </h3>
        <div class="flex justify-center mt-2">
            <BaseButton
                text="Reload Page"
                :href="updatedRoute"
                @click="onReloadClicked"
            />
        </div>
    </Modal>
</template>
