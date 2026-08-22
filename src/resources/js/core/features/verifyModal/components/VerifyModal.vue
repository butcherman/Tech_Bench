<script setup lang="ts">
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import Modal from "@/core/components/Modal.vue";
import { ref } from "vue";

const emit = defineEmits<{
    yesClicked: [];
    noClicked: [];
    hidden: [];
}>();

const props = defineProps<{
    title: string;
    message: string;
}>();

const isOpen = ref(true);

const onYesClicked = () => {
    emit("yesClicked");
    isOpen.value = false;
};

const onNoClicked = () => {
    emit("noClicked");
    isOpen.value = false;
};

const forceOption = () => {
    console.log("tried to close");
};
</script>

<template>
    <Modal
        :show="isOpen"
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
                <BaseButton text="No" variant="danger" @click="onNoClicked" />
                <BaseButton
                    text="Yes"
                    variant="success"
                    @click="onYesClicked"
                />
            </div>
        </template>
    </Modal>
</template>
