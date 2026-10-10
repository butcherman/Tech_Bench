<script setup lang="ts">
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import prettyBytes from "pretty-bytes";
import RangeSliderInput from "@/core/forms/components/validatedInputs/RangeSliderInput.vue";
import SelectGroupedInput from "@/core/forms/components/validatedInputs/SelectGroupedInput.vue";
import SubmitButton from "@/core/components/buttons/SubmitButton.vue";
import TextInput from "@/core/forms/components/validatedInputs/TextInput.vue";
import VueForm from "@/core/forms/components/VueForm.vue";
import { object, string, number, array } from "yup";
import { submit } from "@/wayfinder/routes/init/step-1";
import { welcome } from "@/wayfinder/routes/init";

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
}>();

const initValues = {
    url: props.settings.url,
    company_name: props.settings.company_name,
    timezone: props.settings.timezone,
    max_filesize: props.settings.max_filesize.toString(),
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
        submit-text="Update Application Configuration"
        :initial-values="initValues"
        :submit-route="submit.url()"
        :validation-schema="schema"
        do-not-reset
        @success="$emit('success')"
    >
        <fieldset class="border rounded-xl p-2 flex flex-col gap-3">
            <legend class="text-muted">Basic Settings</legend>
            <TextInput
                label="Site URL"
                name="url"
                help-message="Enter the Primary Site URL"
                help-visible
                focus
            >
                <template #prepend-input>
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
                help-message="Select your local Time Zone"
                help-visible
            />
            <RangeSliderInput
                label="Maximum Uploaded File Size"
                format="prettybytes"
                name="max_filesize"
                help-message="The maximum size that any one uploaded file can be"
                :min="99967437"
                :max="5001634329"
                :value-formatter="prettyBytes"
                help-visible
            >
                <template #value-slot="{ value }">
                    {{ prettyBytes(value) }}
                </template>
            </RangeSliderInput>
        </fieldset>
        <fieldset class="border rounded-xl p-2 mt-4 flex flex-col gap-3">
            <legend class="text-muted">Home Page</legend>
            <TextInput
                name="company_name"
                label="Company Name"
                help-message="Enter your Company Name"
                help-visible
            />
            <TextInput
                name="welcome_message"
                label="Welcome Message"
                help-message="This message will show on the home page under the Company Logo (optional)"
                help-visible
            />
        </fieldset>
        <template #submit-button>
            <div class="flex justify-center gap-3">
                <BaseButton
                    :href="welcome.url()"
                    class="basis-1/3"
                    text="Back"
                />
                <SubmitButton class="basis-1/3" text="Next" />
            </div>
        </template>
    </VueForm>
</template>
