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
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "../_Base/TextInput.vue";
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
    const formData = useForm(form);
    formData.post(route("login"), {
        onFinish: () => userLoginForm.value?.endSubmit(),
        onError: () => shake(document.getElementById("login-form")!!),
    });
};
</script>
