<script setup lang="ts">
import PickListInput from "../_Base/PickListInput.vue";
import SelectInput from "../_Base/SelectInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { object, number, array } from "yup";

defineEmits<{
    success: [];
}>();

const props = defineProps<{
    availableEquipmentList: {
        label: string;
        items: {
            label: string;
            value: number;
        };
    }[];
    customer: customer;
    siteList: customerSite[];
}>();

/*
|-------------------------------------------------------------------------------
| Vee Validate
|-------------------------------------------------------------------------------
*/
const initValues = {
    equip_id: null,
    site_list:
        props.siteList.length === 1 ? [props.siteList[0].cust_site_id] : [],
};
const schema = object({
    equip_id: number().required().label("Equipment Type"),
    site_list: array().required().min(1, "You must select at least one site"),
});
</script>

<template>
    <VueForm
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('customers.equipment.store', customer.slug)"
        submit-method="post"
        submit-text="Add Equipment"
        @success="$emit('success')"
    >
        <SelectInput
            id="equip-id"
            name="equip_id"
            label="Select Equipment"
            :list="availableEquipmentList"
            group-text-field="label"
            group-children-field="items"
            text-field="label"
            value-field="value"
        />
        <PickListInput
            id="equipment-site-list"
            label="Select Which Sites this Equipment Belongs To"
            name="site_list"
            label-field="site_name"
            value-field="cust_site_id"
            :list="siteList"
        />
    </VueForm>
</template>
