<template>
    <VueForm
        ref="loginForm"
        :initial-values="initValues"
        :validation-schema="schema"
        submit-text="Login"
        @submit="onSubmit"
    >
        <TextInput id="username" name="username" placeholder="Username" focus />
        <TextInput
            id="password"
            name="password"
            placeholder="Password"
            type="password"
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
import { useForm } from "@inertiajs/vue3";

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

interface loginForm {
    username: string;
    password: string;
}

const onSubmit = (form: loginForm) => {
    const formData = useForm(form);
    console.log(form);

    formData.post(route("login"), {
        onFinish: () => loginForm.value?.endSubmit(),
        onError: () => loginForm.value?.setValidationErrors(formData.errors),
    });
};
</script>
