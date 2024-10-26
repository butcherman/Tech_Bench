<template>
    <VueForm
        ref="resetPasswordForm"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('password.email')"
        submit-method="post"
        submit-text="Send Recovery Instructions"
    >
        <TextInput id="email" name="email" placeholder="Email Address" focus />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import { ref } from "vue";
import { object, string } from "yup";

const resetPasswordForm = ref<InstanceType<typeof VueForm> | null>(null);
const initValues = {
    email: null,
};
const schema = object({
    email: string().email().required(),
});
</script>
