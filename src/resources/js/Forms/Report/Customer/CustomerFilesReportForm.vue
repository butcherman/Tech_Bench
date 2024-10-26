<template>
    <VueForm
        ref="form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('reports.customer.run-files')"
        submit-method="put"
        submit-text="Run Report"
    >
        <p class="text-center">Show me customers that</p>
        <div class="d-flex flex-row justify-content-center">
            <SelectInput
                id="has-input"
                name="hasInput"
                :list="selectDropdown"
                class="w-50"
            />
        </div>
        <p class="text-center">the following file types.</p>
        <SelectBoxInput
            id="file-types"
            name="fileTypes"
            :list="fileTypes"
            text-field="description"
            value-field="file_type_id"
        />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import SelectInput from "@/Forms/_Base/SelectInput.vue";
import SelectBoxInput from "@/Forms/_Base/SelectBoxInput.vue";
import { array, object, string } from "yup";

defineProps<{
    fileTypes: customerFileType[];
}>();

const initValues = {
    hasInput: null,
    fileTypes: [],
};
const schema = object({
    hasInput: string().required("Please make a selection"),
    fileTypes: array()
        .required()
        .min(1, "You must select at least one file type"),
});

const selectDropdown = ["have", "are missing"];
</script>
