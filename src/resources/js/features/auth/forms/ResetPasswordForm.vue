<script setup lang="ts">
import PasswordInput from "@/core/forms/components/validatedInputs/PasswordInput.vue";
import TextInput from "@/core/forms/components/validatedInputs/TextInput.vue";
import VueForm from "@/core/forms/components/VueForm.vue";
import { object, string, ref as reference } from "yup";
import { update } from "@/wayfinder/routes/password";

defineEmits<{
    success: [];
}>();

const props = defineProps<{
    email: string;
    token: string;
}>();

const initValues = {
    email: props.email,
    token: props.token,
    password: "",
    password_confirmation: "",
};

const schema = object({
    email: string().email().required(),
    token: string().required(),
    password: string().required(),
    password_confirmation: string().oneOf(
        [reference("password")],
        "Passwords must match",
    ),
});
</script>

<template>
    <VueForm
        name="reset-password"
        submit-method="post"
        submit-text="Reset Password"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="update.url()"
        @success="$emit('success')"
    >
        <TextInput name="email" label="Email Address" variant="standard" />
        <PasswordInput
            name="password"
            label="New Password"
            variant="standard"
            focus
        />
        <PasswordInput
            name="password_confirmation"
            label="Confirm Password"
            variant="standard"
        />
    </VueForm>
</template>
