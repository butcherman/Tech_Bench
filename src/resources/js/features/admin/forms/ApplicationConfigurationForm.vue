<script setup lang="ts">
import BaseBadge from "@/core/components/badges/BaseBadge.vue";
import Card from "@/core/components/Card.vue";
import prettyBytes from "pretty-bytes";
import RangeSliderInput from "@/core/forms/components/validatedInputs/RangeSliderInput.vue";
import SelectGroupedInput from "@/core/forms/components/validatedInputs/SelectGroupedInput.vue";
import TextInput from "@/core/forms/components/validatedInputs/TextInput.vue";
import ValidatedArrayWrapper from "@/core/forms/components/wrappers/ValidatedArrayWrapper.vue";
import VueForm from "@/core/forms/components/VueForm.vue";
import { object, string, number, array } from "yup";
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
        :submit-route="update.url()"
        :validation-schema="schema"
        do-not-reset
        @success="$emit('success')"
    >
        <Card title="Basic Settings">
            <div class="flex flex-col gap-4">
                <TextInput
                    label="Site URL"
                    name="url"
                    prepend-text="https://"
                    help-message="The address used to access this application"
                    focus
                />
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
            </div>
        </Card>
        <Card title="Home Page">
            <div class="flex flex-col gap-4">
                <TextInput name="company_name" label="Company Name" />
                <TextInput
                    name="welcome_message"
                    label="Welcome Message"
                    help-message="This message will show on the home page under the Company Logo"
                />
                <div>
                    <h2>Additional Links</h2>
                    <p class="text-muted">
                        Add up to three useful links that will appear on the
                        home page.
                    </p>
                    <div class="border border-slate-300 rounded-lg p-2">
                        <ValidatedArrayWrapper
                            empty-text="No additional links configured"
                            name="home_links"
                            v-slot="{ onRemove, push, fields }"
                            class="flex flex-col gap-4"
                        >
                            <div
                                v-for="(field, index) in fields"
                                class="flex flex-col md:flex-row gap-2"
                                :key="field.key"
                            >
                                <TextInput
                                    :name="`home_links[${index}].url`"
                                    label="URL"
                                />
                                <TextInput
                                    :name="`home_links[${index}].text`"
                                    label="Display Text"
                                />
                                <div
                                    class="flex flex-col md:flex-row items-end text-amber-700"
                                >
                                    <BaseBadge
                                        v-tooltip="`Delete Link`"
                                        icon="trash-alt"
                                        class="pointer"
                                        @click="onRemove(index)"
                                    />
                                </div>
                            </div>
                            <div
                                v-if="fields.length < 3"
                                class="flex flex-row-reverse"
                            >
                                <BaseBadge
                                    text="Add"
                                    icon="plus"
                                    class="pointer"
                                    @click="push({})"
                                />
                            </div>
                        </ValidatedArrayWrapper>
                    </div>
                </div>
            </div>
        </Card>
    </VueForm>
</template>
