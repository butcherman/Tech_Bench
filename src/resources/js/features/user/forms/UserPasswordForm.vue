<script setup lang="ts">
import PasswordInput from "@/core/forms/components/validatedInputs/PasswordInput.vue";
import VueForm from "@/core/forms/components/VueForm.vue";
import { object, string, ref as reference } from "yup";
import { update } from "@/wayfinder/routes/user-password";

defineEmits<{
    success: [];
}>();

const initValues = {
    current_password: "",
    password: "",
    password_confirmation: "",
};
const schema = object({
    current_password: string().required("Enter your current password"),
    password: string().required(),
    password_confirmation: string()
        .required("You must confirm your password")
        .oneOf([reference("password")], "Passwords must match"),
});
</script>

<template>
    <VueForm
        name="user-password-form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="update.url()"
        submit-method="put"
        submit-text="Update Password"
        @success="$emit('success')"
    >
        <PasswordInput name="current_password" label="Current Password" focus />
        <PasswordInput name="password" label="New Password" />
        <PasswordInput name="password_confirmation" label="Confirm Password" />
    </VueForm>
</template>
