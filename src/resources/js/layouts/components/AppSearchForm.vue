<script setup lang="ts">
import VueForm from "@/core/forms/components/VueForm.vue";
import TextInput from "@/core/forms/components/validatedInputs/TextInput.vue";
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
        hide-submit
        @success="$emit('success')"
    >
        <div class="h-full flex items-center">
            <TextInput
                name="search"
                placeholder="Search Equipment, Customers, or Tech Tips"
                label="Search Equipment, Customers, or Tech Tips"
                class="bg-gray-50 rounded-lg"
            >
                <template #prepend-input>
                    <fa-icon icon="magnifying-glass" />
                </template>
            </TextInput>
        </div>
    </VueForm>
</template>
