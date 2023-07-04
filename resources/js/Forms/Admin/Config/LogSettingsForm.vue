<template>
    <VueForm
        ref="logSettingsForm"
        :initial-values="initValues"
        :validation-schema="schema"
        submit-text="Update Log Settings"
        @submit="onSubmit"
    >
        <TextInput
            id="log-days"
            name="days"
            label="Days to keep log files"
            type="number"
        />
        <fieldset>
            <label class="text-center w-100">Log Levels</label>
            <template v-for="channel in channels" :key="channel">
                <SelectInput
                    v-if="channel.name !== 'Emergency'"
                    :id="`channel-${channel.channel}`"
                    :name="`level.${channel.channel}`"
                    :label="channel.name"
                    :list="levels"
                />
            </template>
        </fieldset>
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import SelectInput from "@/Forms/_Base/SelectInput.vue";
import { useForm } from "@inertiajs/vue3";
import { ref, reactive, onMounted } from "vue";
import { object, number, string } from "yup";

const props = defineProps<{
    levels: string[];
    channels: logChannels[];
    days: number;
    values: { [key: string]: string };
}>();

const logSettingsForm = ref<InstanceType<typeof VueForm> | null>(null);
const initValues = {
    days: props.days,
    level: {
        auth: props.values.auth,
        cust: props.values.cust,
        daily: props.values.daily,
        tip: props.values.tip,
        user: props.values.user,
    },
};
const schema = object({
    days: number().required(),
    level: object({
        auth: string().required(),
        cust: string().required(),
        daily: string().required(),
        tip: string().required(),
        user: string().required(),
    }),
});

const onSubmit = (form: { [key: string]: string }) => {
    const formData = useForm(form);

    formData.post(route("admin.logs.settings.set"), {
        onFinish: () => logSettingsForm.value?.endSubmit(),
    });
};
</script>
