<script setup lang="ts">
import PasswordInput from "@/core/forms/components/PasswordInput.vue";
import VueForm from "@/core/forms/components/VueForm.vue";
import { computed } from "vue";
import { object, string, ref as reference } from "yup";
import { resetPassword } from "@/wayfinder/routes/admin/user";
import { update } from "@/wayfinder/routes/initialize";

defineEmits<{
    success: [];
}>();

const props = defineProps<{
    user: User;
    token?: string;
}>();

const submitRoute = computed(() => {
    if (props.token) {
        return update.url(props.token);
    }

    return resetPassword.url(props.user.username);
});

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
        name="reset-password-form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="submitRoute"
        submit-method="put"
        submit-text="Update Password"
        @success="$emit('success')"
    >
        <PasswordInput name="password" label="New Password" />
        <PasswordInput name="password_confirmation" label="Confirm Password" />
    </VueForm>
</template>
