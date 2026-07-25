<script setup lang="ts">
import { ref } from "vue";
import BaseButton from "../buttons/BaseButton.vue";
import BaseModal from "./BaseModal.vue";

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
    </BaseModal>
</template>
