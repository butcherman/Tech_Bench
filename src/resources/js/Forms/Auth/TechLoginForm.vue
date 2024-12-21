<template>
    <Form
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('login')"
        submit-text="Login"
        submit-method="post"
    >
        <TextInput
            id="username"
            label="Username"
            name="username"
            variant="underlined"
            autocomplete="username"
            focus
        />
        <PasswordInput
            id="password"
            label="Password"
            name="password"
            variant="underlined"
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
    </Form>
</template>

<script setup lang="ts">
import Form from "../_Base/VueForm.vue";
import TextInput from "../_Base/TextInput.vue";
import PasswordInput from "../_Base/PasswordInput.vue";
import SwitchInput from "../_Base/SwitchInput.vue";
import { boolean, object, string } from "yup";

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
