<template>
    <VueForm
        ref="userPasswordForm"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('user-password.update')"
        submit-method="put"
        submit-text="Update Password"
    >
        <TextInput
            id="current-password"
            name="current_password"
            label="Current Password"
            type="password"
            v-focus
        />
        <TextInput
            id="password"
            name="password"
            label="New Password"
            type="password"
        />
        <TextInput
            id="password-confirmed"
            name="password_confirmation"
            label="Confirm Password"
            type="password"
        />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import { ref } from "vue";
import { object, string, ref as reference } from "yup";

const userPasswordForm = ref<InstanceType<typeof VueForm> | null>(null);
const initValues = {
    current_password: null,
    password: null,
    password_confirmation: null,
};
const schema = object({
    current_password: string().required("Enter your current password"),
    password: string().required(),
    password_confirmation: string()
        .required("You must confirm your password")
        .oneOf([reference("password")], "Passwords must match"),
});
</script>
