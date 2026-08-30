<script setup lang="ts">
import TextInput from "@/core/forms/components/validatedInputs/TextInput.vue";
import VueForm from "@/core/forms/components/VueForm.vue";
import { object, string } from "yup";
import { computed } from "vue";

defineEmits<{
    success: [];
}>();

const props = defineProps<{
    edit?: any;
}>();

const submitRoute = computed(() => (props.edit ? "#" : "#"));
const submitMethod = computed(() => (props.edit ? "put" : "post"));
const submitText = computed(() => (props.edit ? "Update" : "Submit"));
const initValues = {};
const schema = object({});
</script>

<template>
    <VueForm
        name="form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="submitRoute"
        :submit-method="submitMethod"
        :submit-text="submitText"
        @success="$emit('success')"
    >
        <TextInput id="input" name="input" label="Input" focus />
    </VueForm>
</template>
