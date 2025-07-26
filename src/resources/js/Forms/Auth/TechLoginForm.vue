<script setup lang="ts">
import PasswordInput from "../_Base/PasswordInput.vue";
import SwitchInput from "../_Base/SwitchInput.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
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
    username: string().required("Please enter your Username"),
    password: string().required("Please enter your Password"),
    remember: boolean().required(),
});
</script>

<template>
    <VueForm
        :initial-values="initValues"
        :submit-route="$route('login')"
        :validation-schema="schema"
        submit-text="Login"
        submit-method="post"
        submit-icon="user-check"
        full-page-overlay
    >
        <TextInput
            id="username"
            name="username"
            placeholder="Username"
            help="Enter Your Username"
            borderless
            focus
        />
        <PasswordInput
            id="password"
            name="password"
            placeholder="Password"
            help="Enter Your Password"
            borderless
        />
        <div class="text-right">
            <Link
                :href="$route('password.request')"
                class="text-xs text-blue-400"
                tabindex="-1"
            >
                Forgot Password
            </Link>
        </div>
        <div class="flex justify-center">
            <SwitchInput
                id="remember-me"
                name="remember"
                label="Remember Me"
                center
            />
        </div>
    </VueForm>
</template>
