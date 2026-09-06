<script setup lang="ts">
import Collapse from "@/core/components/Collapse.vue";
import SwitchInput from "@/core/forms/components/validatedInputs/SwitchInput.vue";
import TextAreaInput from "@/core/forms/components/validatedInputs/TextAreaInput.vue";
import VueForm from "@/core/forms/components/VueForm.vue";
import { object, string, boolean } from "yup";
import { store } from "@/wayfinder/routes/admin/security";

defineEmits<{
    success: [];
}>();

const props = defineProps<{
    hasKey: boolean;
}>();

const initValues = {
    wildcard: !props.hasKey,
    certificate: "",
    key: "",
    intermediate: "",
};

const schema = object({
    wildcard: boolean().required(),
    certificate: string().required(),
    key: string().when("wildcard", {
        is: true,
        then: (schema) => schema.required(),
        otherwise: (schema) => schema.nullable(),
    }),
    intermediate: string().required(),
});
</script>

<template>
    <VueForm
        name="upload-cert-form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="store.url()"
        submit-method="post"
        submit-text="Upload Certificate"
        v-slot="{ values }"
        @success="$emit('success')"
    >
        <h6 v-if="!hasKey" class="text-center">
            No Private Key Exists. You must either generate a CSR Request, or
            upload a wildcard cert with key
        </h6>
        <SwitchInput
            name="wildcard"
            label="Upload Wildcard Certificate"
            :data-bs-toggle="hasKey ? 'collapse' : null"
            data-bs-target="#needs-key"
            :disabled="!hasKey"
        />
        <TextAreaInput name="certificate" label="Certificate" :rows="9" />
        <Collapse :show="values.wildcard">
            <TextAreaInput id="key" name="key" label="Private Key" :rows="9" />
        </Collapse>
        <TextAreaInput
            id="intermediate"
            name="intermediate"
            label="Intermediate"
            :rows="9"
        />
    </VueForm>
</template>
