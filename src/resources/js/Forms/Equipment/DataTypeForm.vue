<template>
    <VueForm
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="submitRoute"
        :submit-method="submitMethod"
        :submit-text="submitText"
    >
        <TextInput id="name" name="name" label="Name" />
        <TextInput id="pattern" name="pattern" label="REGEX Pattern">
            <template #start-group-text>
                <div class="input-group-text">/</div>
            </template>
            <template #end-group-text>
                <div class="input-group-text">/ g</div>
            </template>
        </TextInput>
        <CheckboxSwitch id="masked" name="masked" label="Field is Masked" />
        <CheckboxSwitch
            id="is-hyperlink"
            name="is_hyperlink"
            label="Field is A Hyperlink"
        />
        <CheckboxSwitch
            id="allow-copy"
            name="allow_copy"
            label="Add A Copy to Clipboard Button"
        />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import CheckboxSwitch from "../_Base/CheckboxSwitch.vue";
import { computed } from "vue";
import { boolean, object, string } from "yup";

const props = defineProps<{
    dataFieldType?: dataTypes;
}>();

const submitRoute = computed(() =>
    props.dataFieldType
        ? route("equipment-data.update", props.dataFieldType.type_id)
        : route("equipment-data.store")
);
const submitMethod = computed(() => (props.dataFieldType ? "put" : "post"));
const submitText = computed(() =>
    props.dataFieldType ? "Update Data Type" : "Create Data Type"
);

const initValues = {
    name: props.dataFieldType?.name || null,
    pattern: props.dataFieldType?.pattern || null,
    masked: props.dataFieldType?.masked || false,
    is_hyperlink: props.dataFieldType?.is_hyperlink || false,
    allow_copy: props.dataFieldType?.allow_copy || false,
};
const schema = object({
    name: string().required(),
    pattern: string().nullable(),
    masked: boolean().required(),
    is_hyperlink: boolean().required(),
    allow_copy: boolean().required(),
});
</script>
