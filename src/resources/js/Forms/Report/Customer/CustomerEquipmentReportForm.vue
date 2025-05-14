<script setup lang="ts">
import SelectInput from "@/Forms/_Base/SelectInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { object, number } from "yup";

defineProps<{
    equipmentTypes: {
        label: string;
        items: {
            label: string;
            value: number;
        };
    }[];
}>();

/*
|-------------------------------------------------------------------------------
| Vee Validate
|-------------------------------------------------------------------------------
*/
const initValues = {
    equip_id: null,
};
const schema = object({
    equip_id: number().required().label("Equipment Type"),
});
</script>

<template>
    <VueForm
        submit-method="put"
        submit-text="Run Report"
        :initial-values="initValues"
        :submit-route="
            $route('reports.run', ['customers', 'customer-equipment-report'])
        "
        :validation-schema="schema"
    >
        <h3 class="text-center">
            Show me Customers with the following Equipment
        </h3>
        <SelectInput
            id="equip-id"
            name="equip_id"
            label="Select Equipment"
            :list="equipmentTypes"
            group-text-field="label"
            group-children-field="items"
            text-field="label"
            value-field="value"
        />
    </VueForm>
</template>
