<template>
    <VueForm
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('login')"
        submit-text="Login"
        submit-method="post"
        submit-icon="user-check"
        full-page-overlay
    >
        <TextInput
            id="username"
            name="username"
            label="Username"
            help="Enter Your Username"
            border-bottom
            focus
        />
        <PasswordInput
            id="password"
            name="password"
            label="Password"
            help="Enter Your Password"
            border-bottom
        />
        <div class="text-right">
            <Link
                :href="$route('password.request')"
                class="text-xs text-blue-400"
            >
                Forgot Password
            </Link>
        </div>
        <SwitchInput
            id="remember-me"
            name="remember"
            label="Remember Me"
            center
        />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import PasswordInput from "../_Base/PasswordInput.vue";
import SwitchInput from "../_Base/SwitchInput.vue";
import { object, string, boolean } from "yup";

/*
|-------------------------------------------------------------------------------
| Validation
|-------------------------------------------------------------------------------
*/
const initValues = {
    username: null,
    password: null,
    remember: false,
};
const schema = object({
    username: string().required(),
    password: string().required(),
    remember: boolean().required(),
});
</script>
