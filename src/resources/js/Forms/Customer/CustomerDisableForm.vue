<template>
    <VueForm
        ref="form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('customers.destroy', customer.slug)"
        submit-method="delete"
        submit-variant="danger"
        :submit-text="`Disable ${customer.name}`"
    >
        <TextAreaInput
            id="reason"
            name="reason"
            label="Reason For Disabling Customer"
        />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextAreaInput from "../_Base/TextAreaInput.vue";
import { object, string } from "yup";

defineProps<{
    customer: customer;
}>();
const initValues = {
    reason: "",
};
const schema = object({
    reason: string().required(),
});
</script>
