<script setup lang="ts">
import PasswordInput from "@/core/forms/PasswordInput.vue";
import SwitchInput from "@/core/forms/SwitchInput.vue";
import TextInput from "@/core/forms/TextInput.vue";
import VueForm from "@/core/forms/VueForm.vue";
import { login } from "@/wayfinder/routes";
import { request } from "@/wayfinder/routes/password";
import { ref } from "vue";
import { object, string, boolean } from "yup";

const props = defineProps<{
    allowOath: boolean;
}>();

// TODO - Change to only email field when using OATH.
const showPassField = ref(true); //  ref(!props.allowOath);

const initialValues = {
    username: null,
    password: null,
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
    <div>
        <VueForm
            name="login-form"
            submit-method="post"
            submit-text="Login"
            submit-icon="user-check"
            :submit-route="login.url()"
            :validation-schema="validationSchema"
            :initial-values="initialValues"
            full-page-overlay
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
                v-if="showPassField"
                id="password"
                name="password"
                label="Password"
                placeholder="Password"
                input-style="standard"
                help="Enter your password"
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
            <SwitchInput
                id="remember"
                name="remember"
                label="Remember Me"
                center
            />
        </VueForm>
    </div>
</template>
