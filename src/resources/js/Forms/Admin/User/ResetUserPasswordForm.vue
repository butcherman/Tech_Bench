<script setup lang="ts">
import PasswordInput from "../../_Base/PasswordInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { object, string, ref as reference } from "yup";
import { useTemplateRef } from "vue";

const emit = defineEmits<{
    success: [];
}>();

defineProps<{
    user: user;
}>();

const passwordForm = useTemplateRef("password-form");

/*
|-------------------------------------------------------------------------------
| Handle Form
|-------------------------------------------------------------------------------
*/
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
    password: null,
    password_confirmation: null,
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
        ref="password-form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('admin.user.reset-password', user.username)"
        submit-method="put"
        submit-text="Update Password"
        @success="onSuccess"
    >
        <PasswordInput id="password" name="password" label="New Password" />
        <PasswordInput
            id="password-confirmed"
            name="password_confirmation"
            label="Confirm Password"
        />
    </VueForm>
</template>
