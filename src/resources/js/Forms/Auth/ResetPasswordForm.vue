<template>
    <VueForm
        ref="resetPasswordForm"
        :initial-values="initValues"
        :validation-schema="schema"
        submit-text="Reset Password"
        @submit="onSubmit"
    >
        <TextInput id="email" name="email" placeholder="Email Address" />
        <TextInput
            id="password"
            name="password"
            type="password"
            placeholder="New Password"
            focus
        />
        <TextInput
            id="password-confirmation"
            name="password_confirmation"
            type="password"
            placeholder="Confirm Password"
        />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";

import { ref } from "vue";
import { ref as reference, object, string } from "yup";
import { useForm } from "@inertiajs/vue3";

const props = defineProps<{
    email: string;
    token: string;
}>();
const resetPasswordForm = ref<InstanceType<typeof VueForm> | null>(null);
const initValues = {
    email: props.email,
    token: props.token,
    password: null,
    password_confirmation: null,
};
const schema = object({
    email: string().email().required(),
    token: string().required(),
    password: string().required(),
    password_confirmation: string().oneOf(
        [reference("password")],
        "Passwords must match"
    ),
});

interface ResetPasswordForm {
    email: string;
    token: string;
    password: string;
    password_confirmation: string;
}

const onSubmit = (form: ResetPasswordForm) => {
    const formData = useForm(form);
    console.log(form);

    formData.post(route("password.reset"), {
        onFinish: () => resetPasswordForm.value?.endSubmit(),
        onError: () =>
            resetPasswordForm.value?.setValidationErrors(formData.errors),
    });
};
</script>
