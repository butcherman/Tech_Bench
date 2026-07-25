<script setup lang="ts">
import BaseButton from "../buttons/BaseButton.vue";
import BaseModal from "./BaseModal.vue";
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
}>();

const isOpen = ref(true);

const onOkClicked = () => {
    emit("okClicked");
    isOpen.value = false;
};

const forceOption = () => {
    if (props.forceOk) {
        console.log("tried to close");
        return;
    }

    emit("backdropClicked");
    isOpen.value = false;
};
</script>

<template>
    <BaseModal
        :open="isOpen"
        :title="title"
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
    </BaseModal>
</template>
