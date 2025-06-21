<script setup lang="ts">
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import Modal from "@/Components/_Base/Modal.vue";
import { onMounted, ref } from "vue";

const emit = defineEmits<{
    "ok-clicked": [];
    hide: [];
    hidden: [];
}>();

defineProps<{
    status: number;
    message: string;
}>();

const okModal = ref<InstanceType<typeof Modal> | null>(null);

onMounted(() => okModal.value?.show());

/**
 * Close Modal and emit OK was clicked to close out Promise
 */
const handleOkClick = (): void => {
    emit("ok-clicked");
    okModal.value?.hide();
};
</script>

<template>
    <Modal
        ref="okModal"
        position="top"
        @hidden="$emit('hidden')"
        @hide="$emit('hide')"
        hide-close
        :title="`Error ${status}`"
    >
        <h4 class="text-center">{{ message }}</h4>
        <p class="text-center">
            Please try again later or contact System Administrator if issues
            persists.
        </p>
        <template #footer>
            <BaseButton text="OK" @click="handleOkClick" />
        </template>
    </Modal>
</template>
