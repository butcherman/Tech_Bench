<template>
    <VueForm
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('password.email')"
        submit-method="post"
        submit-text="Send Recovery Instructions"
        full-page-overlay
    >
        <TextInput
            id="email"
            name="email"
            label="Email Address"
            type="email"
            focus
            border-bottom
        />
    </VueForm>
</template>

<script setup lang="ts">
import TextInput from "@/Forms/_Base/TextInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { object, string } from "yup";

/*
|-------------------------------------------------------------------------------
| Validation
|-------------------------------------------------------------------------------
*/
const initValues = {
    email: null,
};
const schema = object({
    email: string().email().required(),
});
</script>
