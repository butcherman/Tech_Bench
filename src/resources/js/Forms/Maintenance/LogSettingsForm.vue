<script setup lang="ts">
import SelectInput from "@/Forms/_Base/SelectInput.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { number, object, string } from "yup";

const props = defineProps<{
    days: number;
    logLevel: string;
    levelList: string[];
}>();

const initValues = {
    days: props.days,
    log_level: props.logLevel,
};
const schema = object({
    days: number().required(),
    log_level: string().required(),
});
</script>

<template>
    <VueForm
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('maint.logs.settings.update')"
        submit-method="put"
        submit-text="Update Log Settings"
    >
        <TextInput
            id="days"
            name="days"
            label="Days to keep log files"
            type="number"
            focus
        />
        <SelectInput
            id="log-level"
            name="log_level"
            label="Logging Level"
            :list="levelList"
        />
    </VueForm>
</template>
