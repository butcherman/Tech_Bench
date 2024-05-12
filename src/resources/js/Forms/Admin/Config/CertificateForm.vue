<template>
    <VueForm
        ref="form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('admin.security.store')"
        submit-method="post"
        submit-text="Upload Certificate"
    >
        <h6 v-if="!hasKey" class="text-center">
            No Private Key Exists. You must either generate a CSR Request, or
            upload a wildcard cert with key
        </h6>
        <CheckboxSwitch
            id="isWildcard"
            name="wildcard"
            label="Upload Wildcard Certificate"
            :data-bs-toggle="hasKey ? 'collapse' : null"
            data-bs-target="#needs-key"
            :disabled="!hasKey"
        />
        <TextAreaInput
            id="certificate"
            name="certificate"
            label="Certificate"
            :rows="9"
        />
        <div id="needs-key" class="collapse" :class="{ show: !hasKey }">
            <TextAreaInput id="key" name="key" label="Private Key" :rows="9" />
        </div>
        <TextAreaInput
            id="intermediate"
            name="intermediate"
            label="Intermediate"
            :rows="9"
        />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextAreaInput from "@/Forms/_Base/TextAreaInput.vue";
import CheckboxSwitch from "@/Forms/_Base/CheckboxSwitch.vue";
import { boolean, object, string } from "yup";

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
