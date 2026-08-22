<script setup lang="ts">
import TextInput from "@/core/forms/components/validatedInputs/TextInput.vue";
import VueForm from "@/core/forms/components/VueForm.vue";
import { object, string } from "yup";
import { store } from "@/wayfinder/routes/two-factor/login";

defineEmits<{
    success: [];
}>();

const initValues = {
    recovery_code: "",
};

const schema = object({
    recovery_code: string().required(),
});
</script>

<template>
    <VueForm
        name="two-factor-recovery-code"
        submit-method="post"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="store.url()"
        @success="$emit('success')"
    >
        <TextInput name="recovery_code" label="Enter Recovery Code" focus />
    </VueForm>
</template>
