<script setup lang="ts">
import PasswordInput from "@/core/forms/PasswordInput.vue";
import VueForm from "@/core/forms/VueForm.vue";
import { computed } from "vue";
import { ref as reference, object, string } from "yup";
import { submit } from "@/wayfinder/routes/init/step-4b";
import { update } from "@/wayfinder/routes/user-password";

const emit = defineEmits<{
    success: [];
}>();

const props = defineProps<{
    init?: boolean;
}>();

const submitRoute = computed(() => (props.init ? submit.url() : update.url()));

/*
|-------------------------------------------------------------------------------
| Validation
|-------------------------------------------------------------------------------
*/
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

<template>
    <VueForm
        ref="change-password-form"
        name="change-password-form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="submitRoute"
        submit-method="put"
        submit-text="Update Password"
    >
        <PasswordInput
            id="current-password"
            name="current_password"
            label="Current Password"
            focus
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
