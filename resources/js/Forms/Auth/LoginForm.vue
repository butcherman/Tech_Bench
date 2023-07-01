<template>
    <VueForm
        id="login-form"
        ref="userLoginForm"
        :initial-values="initValues"
        :validation-schema="validationSchema"
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
        <CheckboxSwitch id="remember-me" name="remember" label="Remember Me" class="mb-2" />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import CheckboxSwitch from '@/Forms/_Base/CheckboxSwitch.vue';
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import { object, string } from "yup";
import { shake } from "@/Modules/Animation.module";

type loginForm = {
    username: string;
    password: string;
};

const userLoginForm = ref<InstanceType<typeof VueForm> | null>(null);

const initValues = {
    username: "",
    password: "",
};
const validationSchema = object({
    username: string().required(),
    password: string().required(),
});

const onSubmit = (form: loginForm) => {
    console.log(form);
    const formData = useForm(form);
    formData.post(route("login"), {
        onFinish: () => userLoginForm.value?.endSubmit(),
        onError: () => shake(document.getElementById("login-form")!!),
    });
};
</script>
