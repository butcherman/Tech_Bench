<template>
    <VueForm
        ref="resetPasswordForm"
        :initial-values="initValues"
        :validation-schema="schema"
        submit-text="Send Recovery Instructions"
        @submit="onSubmit"
    >
        <TextInput id="email" name="email" placeholder="Email Address" focus />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";

import { ref } from "vue";
import { object, string } from "yup";
import { useForm } from "@inertiajs/vue3";

const resetPasswordForm = ref<InstanceType<typeof VueForm> | null>(null);
const initValues = {
    email: null,
};
const schema = object({
    email: string().email().required(),
});

const onSubmit = (form: { email: string }) => {
    const formData = useForm(form);
    console.log(form);

    formData.post(route("password.forgot"), {
        onFinish: () => resetPasswordForm.value?.endSubmit(),
        onError: () =>
            resetPasswordForm.value?.setValidationErrors(formData.errors),
    });
};
</script>
