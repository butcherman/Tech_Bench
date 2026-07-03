<script setup lang="ts">
import prettyBytes from "pretty-bytes";
import RangeInput from "@/Forms/_Base/RangeInput.vue";
import SelectInput from "@/Forms/_Base/SelectInput.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { object, string, number, array } from "yup";
import { computed } from "vue";

defineEmits<{
    success: [];
}>();

const props = defineProps<{
    company_name: string;
    maxFilesize: number;
    timezone: string;
    tzList: TimezoneList[];
    url: string;
    welcome_message?: string;
    init?: boolean;
    home_links?: {
        url: string;
        text: string;
    }[];
}>();

/*
|-------------------------------------------------------------------------------
| Handle Form
|-------------------------------------------------------------------------------
*/
const submitRoute = computed(() =>
    props.init
        ? route("init.step-1.submit")
        : route("admin.basic-settings.update"),
);

const submitText = computed(() =>
    props.init ? "Save and Continue" : "Update Application Configuration",
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
    welcome_message: props.welcome_message,
    home_links: props.home_links,
};
const schema = object({
    url: string().required(),
    company_name: string().required().label("Company Name"),
    timezone: string().required(),
    max_filesize: number().required(),
    welcome_message: string().nullable(),
    home_links: array().nullable(),
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
        <fieldset class="border rounded-xl p-2">
            <legend class="text-muted">Basic Settings</legend>
            <TextInput
                id="url"
                label="Site URL"
                name="url"
                prepend="https://"
                focus
            >
                <template #start-text>
                    <span>https://</span>
                </template>
            </TextInput>
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
                :min="99967437"
                :max="5001634329"
            >
                <template #value-slot="{ value }">
                    {{ prettyBytes(value) }}
                </template>
            </RangeInput>
        </fieldset>
        <fieldset class="border rounded-xl p-2 mt-4">
            <legend class="text-muted">Home Page</legend>
            <TextInput
                id="company-name"
                name="company_name"
                label="Company Name"
            />
            <TextInput
                id="welcome-message"
                name="welcome_message"
                label="Welcome Message"
                help="This message will show on the home page under the Company Logo"
            />
            <fieldset class="border rounded-xl p-2 mt-4">
                <legend class="text-muted">
                    Additional Links for Home Page
                </legend>
                <div class="grid grid-cols-2 gap-1">
                    <TextInput
                        id="link-1"
                        name="home_links[0].url"
                        label="URL"
                    />
                    <TextInput
                        id="text-1"
                        name="home_links[0].text"
                        label="Text to Display"
                    />
                    <TextInput
                        id="link-2"
                        name="home_links[1].url"
                        label="URL"
                    />
                    <TextInput
                        id="text-2"
                        name="home_links[1].text"
                        label="Text to Display"
                    />
                    <TextInput
                        id="link-3"
                        name="home_links[2].url"
                        label="URL"
                    />
                    <TextInput
                        id="text-3"
                        name="home_links[2].text"
                        label="Text to Display"
                    />
                </div>
            </fieldset>
        </fieldset>
    </VueForm>
</template>
