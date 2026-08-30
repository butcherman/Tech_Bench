<script setup lang="ts">
import prettyBytes from "pretty-bytes";
import RangeSliderInput from "@/core/forms/components/validatedInputs/RangeSliderInput.vue";
import SelectGroupedInput from "@/core/forms/components/validatedInputs/SelectGroupedInput.vue";
import TextInput from "@/core/forms/components/validatedInputs/TextInput.vue";
import VueForm from "@/core/forms/components/VueForm.vue";
import { object, string, number, array } from "yup";
import { computed } from "vue";
import { submit } from "@/wayfinder/routes/init/step-1";
import { update } from "@/wayfinder/routes/admin/basic-settings";

defineEmits<{
    success: [];
}>();

const props = defineProps<{
    settings: {
        company_name: string;
        max_filesize: number;
        timezone: string;
        url: string;
        welcome_message?: string;
        home_links: {
            url: string;
            text: string;
        }[];
    };
    timezoneList: TimezoneList[];
    init?: boolean;
}>();

const submitRoute = computed(() => (props.init ? submit.url() : update.url()));

const submitText = computed(() =>
    props.init ? "Save and Continue" : "Update Application Configuration",
);

const initValues = {
    url: props.settings.url,
    company_name: props.settings.company_name,
    timezone: props.settings.timezone,
    max_filesize: props.settings.max_filesize,
    welcome_message: props.settings.welcome_message,
    home_links: props.settings.home_links,
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
        name="application-settings-form"
        submit-method="put"
        :initial-values="initValues"
        :submit-route="submitRoute"
        :submit-text="submitText"
        :validation-schema="schema"
        do-not-reset
        @success="$emit('success')"
    >
        <fieldset class="border rounded-xl p-2 flex flex-col gap-3">
            <legend class="text-muted">Basic Settings</legend>
            <TextInput label="Site URL" name="url" prepend="https://" focus>
                <template #start-text>
                    <span>https://</span>
                </template>
            </TextInput>
            <SelectGroupedInput
                group-text-field="label"
                group-list-field="items"
                label="Timezone"
                name="timezone"
                text-field="label"
                value-field="value"
                :list="timezoneList"
            />
            <RangeSliderInput
                label="Maximum Uploaded File Size"
                format="prettybytes"
                name="max_filesize"
                :min="99967437"
                :max="5001634329"
                :value-formatter="prettyBytes"
            >
                <template #value-slot="{ value }">
                    {{ prettyBytes(value) }}
                </template>
            </RangeSliderInput>
        </fieldset>
        <fieldset class="border rounded-xl p-2 mt-4 flex flex-col gap-3">
            <legend class="text-muted">Home Page</legend>
            <TextInput name="company_name" label="Company Name" />
            <TextInput
                name="welcome_message"
                label="Welcome Message"
                help="This message will show on the home page under the Company Logo"
            />
            <fieldset class="border rounded-xl p-2 mt-4 flex flex-col gap-3">
                <legend class="text-muted">
                    Additional Links for Home Page
                </legend>
                <div class="grid grid-cols-2 gap-1">
                    <TextInput :name="`home_links[0].url`" label="URL" />
                    <TextInput
                        name="home_links[0].text"
                        label="Text to Display"
                    />
                    <TextInput name="home_links[1].url" label="URL" />
                    <TextInput
                        name="home_links[1].text"
                        label="Text to Display"
                    />
                    <TextInput name="home_links[2].url" label="URL" />
                    <TextInput
                        name="home_links[2].text"
                        label="Text to Display"
                    />
                </div>
            </fieldset>
        </fieldset>
    </VueForm>
</template>
