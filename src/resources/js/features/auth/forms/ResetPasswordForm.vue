<script setup lang="ts">
import PasswordInput from "@/core/forms/PasswordInput.vue";
import TextInput from "@/core/forms/TextInput.vue";
import VueForm from "@/core/forms/VueForm.vue";
import { ref as reference, object, string } from "yup";
import { update } from "@/wayfinder/routes/password";

const props = defineProps<{
    email: string;
    token: string;
}>();

/*
|-------------------------------------------------------------------------------
| Validation
|-------------------------------------------------------------------------------
*/
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
        "Passwords must match",
    ),
});
</script>

<template>
    <VueForm
        name="reset-password-form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="update.url()"
        submit-method="post"
        submit-text="Reset Password"
    >
        <TextInput
            id="email"
            name="email"
            label="Email Address"
            variant="underlined"
        />
        <PasswordInput
            id="password"
            name="password"
            label="New Password"
            variant="underlined"
            focus
        />
        <PasswordInput
            id="password-confirmation"
            name="password_confirmation"
            label="Confirm Password"
            variant="underlined"
        />
    </VueForm>
</template>
