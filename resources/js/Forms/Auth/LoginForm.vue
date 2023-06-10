<template>
    <VueForm
        :initial-values="initValues"
        :validation-schema="validationSchema"
        submit-text="Login"
        @submit="onSubmit"
    >
        <TextInput
            id="username"
            name="username"
            placeholder="Username"
            focus
        />
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
import { object, string } from "yup";

interface loginForm {
    username: string;
    password: string;
}

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
}
</script>

<style lang="scss">
    .form-control {
        border-top: none;
        border-left: none;
        border-right: none;
    }
</style>
