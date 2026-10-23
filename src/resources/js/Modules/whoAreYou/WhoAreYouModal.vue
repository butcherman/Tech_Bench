<script setup lang="ts">
import SubmitButton from "@/Components/_Base/Buttons/SubmitButton.vue";
import Modal from "@/Components/_Base/Modal.vue";
import { Field, useForm } from "vee-validate";
import { onMounted, ref, useTemplateRef } from "vue";
import { string } from "yup";

const emit = defineEmits<{
    submitted: [string];
}>();

const nameModal = useTemplateRef("who-are-you-modal");

const { handleSubmit, errors } = useForm({
    validationSchema: { name: string().required("Please enter your name") },
    initialValues: { name: "" },
});

const submitName = handleSubmit((form) => {
    emit("submitted", form.name);
    nameModal.value?.hide();
});

onMounted(() => nameModal.value?.show());
</script>

<template>
    <Modal
        ref="who-are-you-modal"
        title="Please Enter Your Name"
        prevent-outside-click
        hide-close
    >
        <form @submit.prevent="submitName">
            <div class="w-full">
                <Field
                    type="text"
                    id="name"
                    name="name"
                    class="border border-slate-500 w-full rounded-lg py-2 px-2"
                    focus
                />
                <div class="text-danger">{{ errors.name }}</div>
            </div>
            <SubmitButton class="bg-blue-300 my-2 rounded-lg p-2 text-white" />
        </form>
    </Modal>
</template>
