<template>
    <VueForm
        ref="resetPasswordForm"
        :initial-values="initValues"
        :validation-schema="validation"
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
import VueForm from "../_Base/VueForm.vue";
import TextInput from "../_Base/TextInput.vue";
import { ref } from "vue";
import { object, string } from "yup";
import { useForm } from "@inertiajs/vue3";

type resetPasswordForm = {
    email: string;
    password: string;
    token: string;
};

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
const validation = object({
    email: string().email().required(),
    password: string().required(),
    password_confirmation: string()
        .required("Enter Password Again")
        .test("confirmed", "Passwords do not match", (value) => {
            return value === resetPasswordForm.value?.getFieldValue("password");
        }),
});

const onSubmit = (form: resetPasswordForm) => {
    const formData = useForm(form);
    formData.post(route("password.reset"), {
        onFinish: () => resetPasswordForm.value?.endSubmit(),
    });
};
</script>
