<template>
    <Card title="File Upload Form">
        <VueFileForm
            :initial-values="initValues"
            :validation-schema="schema"
            :submit-route="submitRoute"
            :submit-method="submitMethod"
            :submit-text="submitText"
        >
            <TextInput
                id="input"
                name="input"
                label="Random Text Input"
                focus
            />
        </VueFileForm>
    </Card>
</template>

<script setup lang="ts">
import Card from "../_Base/Card.vue";
import VueFileForm from "@/Forms/_Base/VueFileForm.vue";
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
