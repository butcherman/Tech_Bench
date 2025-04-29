<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
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
        :validation-schema="schema"
        :submit-route="submitRoute"
        :submit-method="submitMethod"
        :submit-text="submitText"
    >
        <TextInput id="input" name="input" label="Input" focus />
    </VueForm>
</template>
