<script setup lang="ts">
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import Modal from "@/core/components/Modal.vue";
import { ref } from "vue";

const emit = defineEmits<{
    okClicked: [];
    hidden: [];
    backdropClicked: [];
}>();

const props = defineProps<{
    message: string;
    title?: string;
    forceOk?: boolean;
    size?: ComponentSize;
}>();

const isOpen = ref(true);

const onOkClicked = () => {
    emit("okClicked");
    isOpen.value = false;
};

const forceOption = () => {
    if (props.forceOk) {
        return;
    }

    emit("backdropClicked");
    isOpen.value = false;
};
</script>

<template>
    <Modal
        :show="isOpen"
        :title="title"
        :size="size"
        position="top"
        hide-close
        hide-backdrop
        prevent-outside-click
        @hidden="$emit('hidden')"
        @hide-prevented="forceOption"
    >
        {{ message }}
        <template #footer>
            <div class="flex flex-row-reverse gap-2">
                <BaseButton text="OK" variant="success" @click="onOkClicked" />
            </div>
        </template>
    </Modal>
</template>
