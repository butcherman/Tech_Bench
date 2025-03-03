<script setup lang="ts">
import RangeInput from "@/Forms/_Base/RangeInput.vue";
import SelectInput from "@/Forms/_Base/SelectInput.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { object, string, number } from "yup";
import { computed } from "vue";
import prettyBytes from "pretty-bytes";

defineEmits(["success"]);
const props = defineProps<{
    company_name: string;
    maxFilesize: number;
    timezone: string;
    tzList: TimezoneList[];
    url: string;
    init?: boolean;
}>();

/*
|-------------------------------------------------------------------------------
| Handle Form
|-------------------------------------------------------------------------------
*/
const submitRoute = computed(() =>
    props.init
        ? route("init.step-1.submit")
        : route("admin.basic-settings.update")
);

const submitText = computed(() =>
    props.init ? "Save and Continue" : "Update Application Configuration"
);

/*
|-------------------------------------------------------------------------------
| Validation
|-------------------------------------------------------------------------------
*/
const initValues = {
    url: props.url,
    company_name: props.company_name,
    timezone: props.timezone,
    max_filesize: props.maxFilesize,
};
const schema = object({
    url: string().required(),
    company_name: string().required().label("Company Name"),
    timezone: string().required(),
    max_filesize: number().required(),
});
</script>

<template>
    <VueForm
        submit-method="put"
        :initial-values="initValues"
        :submit-route="submitRoute"
        :submit-text="submitText"
        :validation-schema="schema"
        @success="$emit('success')"
    >
        <TextInput
            id="url"
            label="Site URL"
            name="url"
            prepend="https://"
            focus
        >
            <template #start-group-text>
                <span>https://</span>
            </template>
        </TextInput>
        <TextInput id="company-name" name="company_name" label="Company Name" />
        <SelectInput
            id="timezone"
            group-text-field="label"
            group-children-field="items"
            label="Timezone"
            name="timezone"
            text-field="label"
            value-field="value"
            :list="tzList"
        />
        <RangeInput
            id="file-size"
            label="Maximum Uploaded File Size"
            format="prettybytes"
            name="max_filesize"
            :min="5000"
            :max="10737418240"
        >
            <template #value-slot="{ value }">
                {{ prettyBytes(value) }}
            </template>
        </RangeInput>
    </VueForm>
</template>
