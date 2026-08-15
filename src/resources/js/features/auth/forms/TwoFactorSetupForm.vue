<script setup lang="ts">
import OtpInput from "@/core/forms/components/validatedInputs/OtpInput.vue";
import VueForm from "@/core/forms/components/VueForm.vue";
import { confirm } from "@/wayfinder/routes/two-factor";
import { object, number } from "yup";
import type { Page, PageProps } from "@inertiajs/core";

defineEmits<{
    success: [Page<PageProps>];
}>();

const initValues = {
    code: "",
};
const schema = object({
    code: number().required("Please Input the Code from the Authenticator App"),
});
</script>

<template>
    <VueForm
        name="two-fa-setup-form"
        submit-method="post"
        submit-text="Confirm Code"
        :initial-values="initValues"
        :submit-route="confirm.url()"
        :validation-schema="schema"
        @success="$emit('success', $event)"
    >
        <OtpInput name="code" :length="6" v-focus />
    </VueForm>
</template>
