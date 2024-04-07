<template>
    <VueForm
        ref="form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('customers.equipment.store', customer.slug)"
        submit-method="post"
        submit-text="Add Equipment"
        @success="$emit('success')"
        @submitting="$emit('submitting')"
        @has-errors="$emit('has-errors')"
    >
        <SelectInput
            id="equip-id"
            name="equip_id"
            label="Select Equipment Type"
            :list="equipmentList"
        />
        <SelectBoxInput
            v-if="siteList.length > 1"
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
import SelectInput from "@/Forms/_Base/SelectInput.vue";
import SelectBoxInput from "../_Base/SelectBoxInput.vue";
import { array, number, object } from "yup";

defineEmits(["success", "submitting", "has-errors"]);
const props = defineProps<{
    equipmentList: {
        [key: string]: { text: string; value: string | number }[];
    };
    customer: customer;
    siteList: customerSite[];
}>();

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
