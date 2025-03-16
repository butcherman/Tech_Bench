<script setup lang="ts">
import Collapse from "@/Components/_Base/Collapse.vue";
import SwitchInput from "@/Forms/_Base/SwitchInput.vue";
import TextAreaInput from "@/Forms/_Base/TextAreaInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { boolean, object, string } from "yup";
import { ref } from "vue";

const props = defineProps<{
    hasKey: boolean;
}>();

const showKey = ref<boolean>(!props.hasKey);

/*
|-------------------------------------------------------------------------------
| Vee Validate
|-------------------------------------------------------------------------------
*/
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
        <SwitchInput
            id="isWildcard"
            name="wildcard"
            label="Upload Wildcard Certificate"
            :data-bs-toggle="hasKey ? 'collapse' : null"
            data-bs-target="#needs-key"
            :disabled="!hasKey"
            @change="showKey = !showKey"
        />
        <TextAreaInput
            id="certificate"
            name="certificate"
            label="Certificate"
            :rows="9"
        />
        <Collapse :show="showKey">
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
