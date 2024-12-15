<template>
    <VueForm
        ref="form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="submitRoute"
        submit-method="put"
        submit-text="Run Report"
    >
        <div class="d-flex justify-content-center">
            <CheckboxSwitch
                id="disabled-customers"
                name="disabledCustomers"
                label="Include Disabled Customers"
            />
        </div>
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import CheckboxSwitch from "@/Forms/_Base/CheckboxSwitch.vue";
import { boolean, object } from "yup";

defineProps<{
    submitRoute: string;
}>();

const initValues = {
    disabledCustomers: false,
};
const schema = object({
    disabledCustomers: boolean().required(),
});
</script>
