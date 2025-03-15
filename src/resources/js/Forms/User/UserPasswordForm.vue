<script setup lang="ts">
import PasswordInput from "../_Base/PasswordInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { object, string, ref as reference } from "yup";
import { computed, useTemplateRef } from "vue";

const emit = defineEmits<{
    success: [];
}>();

const props = defineProps<{
    init?: boolean;
}>();

const passwordForm = useTemplateRef("password-form");

/*
|-------------------------------------------------------------------------------
| Handle Form
|-------------------------------------------------------------------------------
*/
const submitRoute = computed(() =>
    props.init ? route("init.step-4b.submit") : route("user-password.update")
);

const onSuccess = () => {
    emit("success");
    passwordForm.value?.resetForm();
};

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
        ref="password-form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="submitRoute"
        submit-method="put"
        submit-text="Update Password"
        @success="onSuccess"
    >
        <PasswordInput
            id="current-password"
            name="current_password"
            label="Current Password"
            focus
        />
        <PasswordInput id="password" name="password" label="New Password" />
        <PasswordInput
            id="password-confirmed"
            name="password_confirmation"
            label="Confirm Password"
        />
    </VueForm>
</template>
