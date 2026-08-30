<script setup lang="ts">
import PasswordInput from "@/core/forms/components/validatedInputs/PasswordInput.vue";
import VueForm from "@/core/forms/components/VueForm.vue";
import { object, string, ref as reference } from "yup";
import { resetPassword } from "@/wayfinder/routes/admin/user";

defineEmits<{
    success: [];
}>();

const props = defineProps<{
    user: User;
}>();

const initValues = {
    password: "",
    password_confirmation: "",
};

const schema = object({
    password: string().required(),
    password_confirmation: string()
        .required("You must confirm your password")
        .oneOf([reference("password")], "Passwords must match"),
});
</script>

<template>
    <VueForm
        name="form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="resetPassword.url(user.username)"
        submit-method="put"
        submit-text="Set Password"
        @success="$emit('success')"
    >
        <PasswordInput id="password" name="password" label="New Password" />
        <PasswordInput
            id="password-confirmed"
            name="password_confirmation"
            label="Confirm Password"
        />
    </VueForm>
</template>
