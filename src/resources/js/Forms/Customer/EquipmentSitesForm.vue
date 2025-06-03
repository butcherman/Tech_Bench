<script setup lang="ts">
import PickListInput from "../_Base/PickListInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { object, array, number } from "yup";

defineEmits<{
    success: [];
}>();

const props = defineProps<{
    customer: customer;
    equipment: customerEquipment;
    siteList: customerSite[];
}>();

/*
|-------------------------------------------------------------------------------
| Vee Validate
|-------------------------------------------------------------------------------
*/
const initValues = {
    equip_id: props.equipment.equip_id,
    site_list: props.siteList.map((site) => site.cust_site_id),
};
const schema = object({
    equip_id: number().required(),
    site_list: array().nullable(),
});
</script>

<template>
    <VueForm
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="
            $route('customers.equipment.update', [
                customer.slug,
                equipment.cust_equip_id,
            ])
        "
        submit-method="put"
        submit-text="Update Equipment Sites"
        @success="$emit('success')"
    >
        <PickListInput
            id="equipment-site-list"
            label="Select Which Sites this Equipment Belongs To"
            name="site_list"
            label-field="site_name"
            value-field="cust_site_id"
            :list="customer.sites"
        />
    </VueForm>
</template>
