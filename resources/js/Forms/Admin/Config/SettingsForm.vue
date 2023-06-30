<template>
    <VueForm
        ref="settingsForm"
        :initial-values="initValues"
        :validation-schema="schema"
        submit-text="Update Application Configuration"
        @submit="onSubmit"
    >
        <TextInput id="url" name="url" label="Site URL" />
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
            format="prettybytes"
            :min="5000"
            :max="10737418240"
        />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import RangeInput from "@/Forms/_Base/RangeInput.vue";
import SelectInput from "@/Forms/_Base/SelectInput.vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { object, string, number } from "yup";

const props = defineProps<{
    settings: appConfig;
    tzList: tzList;
}>();

const settingsForm = ref<InstanceType<typeof VueForm> | null>(null);
const initValues = {
    url: props.settings.url,
    timezone: props.settings.timezone,
    max_filesize: props.settings.max_filesize,
};
const schema = object({
    url: string().required(),
    timezone: string().required(),
    max_filesize: number().required(),
});

const onSubmit = (form: appConfig) => {
    const formData = useForm(form);

    formData.post(route("admin.config.set"), {
        onFinish: () => settingsForm.value?.endSubmit(),
    });
};
</script>
