<template>
    <VueForm
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="
            init
                ? $route('init.step-2.submit')
                : $route('basic-settings.update')
        "
        submit-method="put"
        submit-text="Update Application Configuration"
        @success="$emit('success')"
    >
        <TextInput id="url" name="url" label="Site URL" focus>
            <template #start-group-text>
                <span class="input-group-text">https://</span>
            </template>
        </TextInput>
        <SelectInput
            id="timezone"
            name="timezone"
            label="Timezone"
            :list="tzList"
        />
        <RangeInput
            id="file-size"
            name="max_filesize"
            label="Maximum Uploaded File Size"
            :min="5000"
            :max="10737418240"
            format="prettybytes"
        />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import SelectInput from "@/Forms/_Base/SelectInput.vue";
import RangeInput from "@/Forms/_Base/RangeInput.vue";
import { number, object, string } from "yup";

defineEmits(["success"]);
const props = defineProps<{
    tzList: TimezoneList;
    url: string;
    timezone: string;
    maxFilesize: number;
    init?: boolean;
}>();

const initValues = {
    url: props.url,
    timezone: props.timezone,
    max_filesize: props.maxFilesize,
};
const schema = object({
    url: string().required(),
    timezone: string().required(),
    max_filesize: number().required(),
});
</script>
