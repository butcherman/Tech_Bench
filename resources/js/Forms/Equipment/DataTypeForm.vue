<template>
    <VueForm
        ref="dataTypeForm"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-text="submitText"
        @submit="onSubmit"
    >
        <TextInput id="name" name="name" label="Name" />
        <TextInput id="pattern" name="pattern" label="REGEX Pattern">
            <template #start-group-text>
                <div class="input-group-text">/</div>
            </template>
            <template #end-group-text>
                <div class="input-group-text">/ g</div>
            </template>
        </TextInput>
        <CheckboxSwitch
            id="required"
            name="required"
            label="Field is Required"
        />
        <CheckboxSwitch id="masked" name="masked" label="Field is Masked" />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import CheckboxSwitch from "../_Base/CheckboxSwitch.vue";
import { useForm } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import { object, string, boolean } from "yup";

const props = defineProps<{
    dataType?: dataTypes;
}>();

const dataTypeForm = ref<InstanceType<typeof VueForm> | null>(null);
const submitText = computed(() =>
    props.dataType ? "Update Data Type " : "Create Data Type"
);
const initValues = {
    name: props.dataType ? props.dataType.name : "",
    pattern: props.dataType ? props.dataType.pattern : "",
    required: props.dataType ? props.dataType.required : false,
    masked: props.dataType ? props.dataType.masked : false,
};
const schema = object({
    name: string().required(),
    pattern: string().nullable(),
    required: boolean().required(),
    masked: boolean().required(),
});

const onSubmit = (form: dataTypes) => {
    const formData = useForm(form);

    if(props.dataType) {
        formData.put(route('data-types.update', props.dataType.type_id), {
            onFinish: () => dataTypeForm.value?.endSubmit(),
        });
    } else {
        formData.post(route("data-types.store"), {
            onFinish: () => dataTypeForm.value?.endSubmit(),
        });
    }
};
</script>
