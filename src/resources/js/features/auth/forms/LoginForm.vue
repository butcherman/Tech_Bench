<script setup lang="ts">
import PasswordInput from "@/core/forms/components/validatedInputs/PasswordInput.vue";
import TextInput from "@/core/forms/components/validatedInputs/TextInput.vue";
import VueForm from "@/core/forms/components/VueForm.vue";
import { object, string, boolean } from "yup";
import { login } from "@/wayfinder/routes";
import { request } from "@/wayfinder/routes/password";
import SwitchInput from "@/core/forms/components/validatedInputs/SwitchInput.vue";

defineEmits<{
    success: [];
}>();

const props = defineProps<{
    allowOath: boolean;
}>();

const initialValues = {
    username: "",
    password: "",
    remember: false,
};
const validationSchema = object({
    username: string().required("Please enter your username or email"),
    password: !props.allowOath
        ? string().required("Please enter your password")
        : string().nullable(),
    remember: boolean().required(),
});
</script>

<template>
    <VueForm
        name="login-form"
        submit-method="post"
        submit-text="Login"
        submit-icon="user-check"
        :submit-route="login.url()"
        :validation-schema="validationSchema"
        :initial-values="initialValues"
        full-page-overlay
        @success="$emit('success')"
    >
        <TextInput
            name="username"
            label="Username"
            placeholder="Username"
            input-style="standard"
            help="Enter your username or email address"
            variant="standard"
            hide-help
        />
        <PasswordInput
            name="password"
            label="Password"
            placeholder="Password"
            input-style="standard"
            help="Enter your password"
            variant="standard"
            hide-help
        />
        <div class="text-right">
            <Link
                :href="request.url()"
                class="text-xs text-blue-400"
                tabindex="-1"
            >
                Forgot Password
            </Link>
        </div>
        <SwitchInput name="remember" label="Remember Me" />
    </VueForm>
</template>
