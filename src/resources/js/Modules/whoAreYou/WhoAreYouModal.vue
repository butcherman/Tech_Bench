<script setup lang="ts">
import SubmitButton from "@/Components/_Base/Buttons/SubmitButton.vue";
import Modal from "@/Components/_Base/Modal.vue";
import { onMounted, ref, useTemplateRef } from "vue";

const emit = defineEmits<{
    submitted: [string];
}>();

const nameModal = useTemplateRef("who-are-you-modal");
const myName = ref();

const submitName = () => {
    emit("submitted", myName.value);
    nameModal.value?.hide();

    // TODO - Validate name
};

onMounted(() => nameModal.value?.show());
</script>

<template>
    <Modal ref="who-are-you-modal" title="Please Enter Your Name">
        <form @submit.prevent="submitName">
            <div class="w-full flex">
                <input
                    v-model="myName"
                    type="text"
                    id="name"
                    name="name"
                    class="border border-slate-500 w-full rounded-lg py-2 px-2"
                    focus
                />
            </div>
            <SubmitButton class="bg-blue-300 my-2 rounded-lg p-2 text-white" />
        </form>
    </Modal>
</template>
