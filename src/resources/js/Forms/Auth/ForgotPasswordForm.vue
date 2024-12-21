<template>
    <VueForm
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('password.email')"
        submit-method="post"
        submit-text="Send Recovery Instructions"
    >
        <TextInput
            id="email"
            name="email"
            label="Email Address"
            variant="underlined"
            focus
        />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import { object, string } from "yup";

const initValues = {
    email: null,
};
const schema = object({
    email: string().email().required(),
});
</script>
