<script setup lang="ts">
import TextInput from "@/Forms/_Base/TextInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { object, string } from "yup";
import { computed } from "vue";

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
        :initial-values="initValues"
        :submit-method="submitMethod"
        :submit-route="submitRoute"
        :submit-text="submitText"
        :validation-schema="schema"
    >
        <TextInput id="input" name="input" label="Input" focus />
    </VueForm>
</template>
