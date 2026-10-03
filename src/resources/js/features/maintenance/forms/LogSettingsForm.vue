<script setup lang="ts">
import SelectInput from "@/core/forms/components/validatedInputs/SelectInput.vue";
import TextInput from "@/core/forms/components/validatedInputs/TextInput.vue";
import VueForm from "@/core/forms/components/VueForm.vue";
import { object, string, number } from "yup";
import { update } from "@/wayfinder/routes/maint/logs/settings";

defineEmits<{
    success: [];
}>();

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
        name="log-settings-form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="update.url()"
        submit-method="put"
        submit-text="Update Log Settings"
        do-not-reset
        @success="$emit('success')"
    >
        <TextInput
            name="days"
            label="Days to keep log files"
            type="number"
            focus
        />
        <SelectInput name="log_level" label="Logging Level" :list="levelList" />
    </VueForm>
</template>
