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
        <SelectBoxInput
            id="equipment-site-list"
            name="site_list"
            label="Select Which Sites have this Equipment"
            text-field="site_name"
            value-field="cust_site_id"
            :list="siteList"
        />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import SelectBoxInput from "@/Forms/_Base/SelectBoxInput.vue";
import { array, number, object } from "yup";

defineEmits(["success"]);
const props = defineProps<{
    customer: customer;
    equipment: customerEquipment;
    currentList: customerSite[];
    siteList: customerSite[];
}>();

const initValues = {
    site_list: props.currentList.map((site) => site.cust_site_id),
    equip_id: props.equipment.equip_id,
};
const schema = object({
    site_list: array().nullable(),
    equip_id: number().required(),
});
</script>
