<script setup lang="ts">
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
    remembrer: boolean().required(),
});
</script>

<template>
    <div>
        <VueForm
            name="login-form"
            submit-method="post"
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
            />
        </VueForm>
    </div>
</template>
