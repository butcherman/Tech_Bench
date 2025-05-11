<script setup lang="ts">
import PickListInput from "@/Forms/_Base/PickListInput.vue";
import SelectInput from "@/Forms/_Base/SelectInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { object, string, array } from "yup";

defineProps<{
    fileTypes: customerFileType[];
}>();

const selectDropdown = ["have", "are missing"];

/*
|-------------------------------------------------------------------------------
| Vee Validate
|-------------------------------------------------------------------------------
*/
const initValues = {
    hasInput: null,
    file_types: [],
};
const schema = object({
    hasInput: string().required("Please make a selection"),
    file_types: array()
        .required()
        .min(1, "You must select at least one file type"),
});
</script>

<template>
    <VueForm
        submit-method="put"
        submit-text="Run Report"
        :initial-values="initValues"
        :submit-route="
            $route('reports.run', ['customers', 'customer-files-report'])
        "
        :validation-schema="schema"
    >
        <p class="text-center">Show me customers that</p>
        <div class="flex justify-center">
            <div class="w-1/2">
                <SelectInput
                    id="has-input"
                    name="hasInput"
                    :list="selectDropdown"
                    class="w-50"
                />
            </div>
        </div>
        <p class="text-center">the following file types.</p>
        <PickListInput
            id="file-types"
            name="file_types"
            :list="fileTypes"
            label-field="description"
            value-field="file_type_id"
        />
    </VueForm>
</template>
