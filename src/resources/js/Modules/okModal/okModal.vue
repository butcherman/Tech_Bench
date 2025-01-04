<template>
    <Modal
        ref="okModal"
        position="top"
        :prevent-outside-click="forceOk"
        @hidden="$emit('hidden')"
        @hide="$emit('hide')"
        @hide-prevented="handleHidePrevented"
        hide-close
    >
        <h4 class="text-center">{{ message }}</h4>
        <template #footer>
            <BaseButton
                text="OK"
                :class="{ 'shake-me': shakeOk }"
                @click="handleOkClick"
            />
        </template>
    </Modal>
</template>

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
    message: string;
    forceOk: boolean;
}>();

const okModal = ref<InstanceType<typeof Modal> | null>(null);
const shakeOk = ref<boolean>(false);

onMounted(() => okModal.value?.show());

/**
 * Close Modal and emit OK was clicked to close out Promise
 */
const handleOkClick = (): void => {
    emit("ok-clicked");
    okModal.value?.hide();
};

/**
 * Shake the OK button to let the user know they must click on it
 */
const handleHidePrevented = (): void => {
    shakeOk.value = true;

    setTimeout(() => (shakeOk.value = false), 1000);
};
</script>
