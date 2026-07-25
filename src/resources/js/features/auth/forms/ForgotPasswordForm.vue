<script setup lang="ts">
import TextInput from "@/core/forms/TextInput.vue";
import VueForm from "@/core/forms/VueForm.vue";
import { email } from "@/wayfinder/routes/password";
import { object, string } from "yup";

defineEmits<{
    success: [];
}>();

const initValues = {
    email: null,
};
const schema = object({
    email: string().email().required("Enter your email address"),
});
</script>

<template>
    <VueForm
        name="form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="email.url()"
        submit-method="post"
        submit-text="Send Instructions"
        @success="$emit('success')"
    >
        <TextInput
            id="email"
            name="email"
            label="Email Address"
            placeholder="Email Address"
        />
    </VueForm>
</template>
