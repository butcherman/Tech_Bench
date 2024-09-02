<template>
    <VueForm
        ref="loginForm"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('login')"
        submit-text="Login"
        submit-method="post"
    >
        <TextInput id="username" name="username" placeholder="Username" focus />
        <TextInput
            id="password"
            name="password"
            placeholder="Password"
            type="password"
            allow-show-pass
        />
        <div class="text-center mb-2">
            <CheckboxSwitch
                id="remember-me"
                name="remember"
                label="Remember Me"
                inline
            />
        </div>
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import CheckboxSwitch from "../_Base/CheckboxSwitch.vue";
import { ref } from "vue";
import { boolean, object, string } from "yup";

const loginForm = ref<InstanceType<typeof VueForm> | null>(null);
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
