<script setup lang="ts">
import TextInput from "@/core/forms/components/validatedInputs/TextInput.vue";
import VueForm from "@/core/forms/components/VueForm.vue";
import { email } from "@/wayfinder/routes/password";
import { object, string } from "yup";

defineEmits<{
    success: [];
}>();

const initValues = {
    email: "",
};
const schema = object({
    email: string().email().required(),
});
</script>

<template>
    <VueForm
        name="forgot-password"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="email.url()"
        submit-method="post"
        submit-text="Send Recovery Instructions"
        full-page-overlay
        @success="$emit('success')"
    >
        <TextInput
            name="email"
            label="Email Address"
            type="email"
            variant="standard"
            focus
        />
    </VueForm>
</template>
