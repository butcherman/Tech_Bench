<script setup lang="ts">
import PasswordInput from "../_Base/PasswordInput.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { ref as reference, object, string } from "yup";

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
        "Passwords must match"
    ),
});
</script>

<template>
    <VueForm
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('password.update')"
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
