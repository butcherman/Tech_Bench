<template>
    <VueForm
        ref="form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="
            $route('customers.sites.destroy', [customer.slug, site.site_slug])
        "
        submit-method="delete"
        submit-variant="danger"
        :submit-text="`Disable ${site.site_name}`"
        @success="$emit('success')"
    >
        <TextAreaInput
            id="reason"
            name="reason"
            label="Reason For Disabling Site"
        />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextAreaInput from "../_Base/TextAreaInput.vue";
import { object, string } from "yup";

defineEmits(["success"]);
defineProps<{
    customer: customer;
    site: customerSite;
}>();
const initValues = {
    reason: "",
};
const schema = object({
    reason: string().required(),
});
</script>
