<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import { object, string } from "yup";
import { computed, ref } from "vue";
import SelectInput from "../_Base/SelectInput.vue";
import PickListInput from "../_Base/PickListInput.vue";

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
const initValues = {};
const schema = object({});
</script>

<template>
    <VueForm
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('customers.equipment.store', customer.slug)"
        submit-method="post"
        submit-text="Add Equipment"
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
