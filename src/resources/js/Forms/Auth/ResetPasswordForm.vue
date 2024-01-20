<template>
    <VueForm
        ref="resetPasswordForm"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('password.reset')"
        submit-method="post"
        submit-text="Reset Password"
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
</script>
