<script setup lang="ts">
import OtpInput from "../_Base/OtpInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { number, object } from "yup";

defineEmits<{
    success: [];
}>();

/*
|-------------------------------------------------------------------------------
| Vee Validate
|-------------------------------------------------------------------------------
*/
const initValues = {
    code: "",
};
const schema = object({
    code: number().required("Please Input the Code from the Authenticator App"),
});
</script>

<template>
    <VueForm
        :initial-values="initValues"
        submit-method="post"
        :submit-route="$route('two-factor.confirm')"
        submit-text="Confirm Code"
        :validation-schema="schema"
        @success="$emit('success')"
    >
        <OtpInput id="code" name="code" :length="6" />
    </VueForm>
</template>
