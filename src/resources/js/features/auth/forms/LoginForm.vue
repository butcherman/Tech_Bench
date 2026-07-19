<script setup lang="ts">
import PasswordInput from "@/core/forms/PasswordInput.vue";
import SwitchInput from "@/core/forms/SwitchInput.vue";
import TextInput from "@/core/forms/TextInput.vue";
import VueForm from "@/core/forms/VueForm.vue";
import { login } from "@/wayfinder/routes";
import { object, string, boolean } from "yup";

const initialValues = {
    username: null,
    password: null,
    remember: false,
};
const validationSchema = object({
    username: string().required("Please enter your username or email"),
    password: string().required("Please enter your password"),
    // remembrer: boolean().required(),
});
</script>

<template>
    <div>
        <VueForm
            name="login-form"
            submit-method="post"
            submit-text="Login"
            submit-icon="user-check"
            :submit-route="login.url()"
            :validation-schema="validationSchema"
            :initial-values="initialValues"
        >
            <TextInput
                id="username"
                name="username"
                label="Username"
                placeholder="Username"
                input-style="standard"
                help="Enter your username or email address"
                hide-help
            />
            <PasswordInput
                id="password"
                name="password"
                label="Password"
                placeholder="Password"
                input-style="standard"
                help="Enter your password"
                hide-help
            />
            <SwitchInput
                id="remember"
                name="remember"
                label="Remember Me"
                center
            />
        </VueForm>
    </div>
</template>
