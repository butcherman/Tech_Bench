<script setup lang="ts">
import PickListInput from "@/Forms/_Base/PickListInput.vue";
import SelectInput from "@/Forms/_Base/SelectInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { object, string, array } from "yup";

defineProps<{
    fileTypes: customerFileType[];
    equipmentTypes: equipmentCategory[];
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
    has_equipment: null,
};
const schema = object({
    hasInput: string().required("Please make a selection"),
    file_types: array()
        .required()
        .min(1, "You must select at least one file type"),
    has_equipment: string().nullable(),
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
            <div>
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
        <SelectInput
            id="has-equipment"
            name="has_equipment"
            label="Limit to Customers with this Equipment"
            group-text-field="label"
            group-children-field="items"
            text-field="label"
            value-field="value"
            :list="equipmentTypes"
        />
    </VueForm>
</template>
