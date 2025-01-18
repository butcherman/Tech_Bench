<template>
    <Modal
        ref="verifyModal"
        :title="title"
        position="top"
        @hidden="$emit('hidden')"
        @hide="$emit('hide')"
        hide-close
    >
        <h4 class="text-center">{{ message }}</h4>
        <template #footer>
            <BaseButton
                text="Yes"
                variant="danger"
                class="mx-2"
                @click="yesClicked"
            />
            <BaseButton text="No" class="mx-2" @click="noClicked" />
        </template>
    </Modal>
</template>

<script setup lang="ts">
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import Modal from "@/Components/_Base/Modal.vue";
import { onMounted, ref } from "vue";

const emit = defineEmits<{
    "yes-clicked": [];
    "no-clicked": [];
    hide: [];
    hidden: [];
}>();

defineProps<{
    title: string;
    message: string;
}>();

const verifyModal = ref<InstanceType<typeof Modal> | null>(null);

function yesClicked(): void {
    emit("yes-clicked");
    verifyModal.value?.hide();
}

function noClicked(): void {
    emit("no-clicked");
    verifyModal.value?.hide();
}

onMounted(() => verifyModal.value?.show());
</script>
