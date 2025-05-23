<script setup lang="ts">
import TextAreaInput from "../_Base/TextAreaInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { object, string } from "yup";

defineEmits<{
    success: [];
}>();

defineProps<{
    customer: customer;
}>();

/*
|-------------------------------------------------------------------------------
| Vee Validate
|-------------------------------------------------------------------------------
*/
const initValues = {
    reason: "",
};
const schema = object({
    reason: string().required(),
});
</script>

<template>
    <VueForm
        ref="form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('customers.destroy', customer.slug)"
        submit-method="delete"
        submit-variant="danger"
        :submit-text="`Disable ${customer.name}`"
        @success="$emit('success')"
    >
        <p class="text-center">
            Please note why this customer is being disabled.
        </p>
        <TextAreaInput
            id="reason"
            name="reason"
            label="Reason For Disabling Customer"
        />
    </VueForm>
</template>
