<script setup lang="ts">
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import ResourceList from "@/Components/_Base/ResourceList.vue";
import SwitchInput from "@/Forms/_Base/SwitchInput.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { array, boolean, object } from "yup";
import { useTemplateRef, watch } from "vue";

const emit = defineEmits<{
    remove: [customer];
    showList: [boolean];
}>();

const props = defineProps<{
    customerList: customer[];
}>();

const form = useTemplateRef("report-form");

watch(props.customerList, (newCustList) =>
    form.value?.setFieldValue("customer_list", newCustList)
);

const removeCust = (cust: customer) => {
    emit("remove", cust);
};

/*
|-------------------------------------------------------------------------------
| Vee Validate
|-------------------------------------------------------------------------------
*/
const initValues = {
    all_customers: true,
    customer_list: props.customerList,
};
const schema = object({
    all_customers: boolean().required(),
    customer_list: array().when("all_customers", {
        is: false,
        then: (schema) =>
            schema.min(1, "Select at least one customer to run the report for"),
        otherwise: (schema) => schema.nullable(),
    }),
});
</script>

<template>
    <VueForm
        ref="report-form"
        submit-text="Run Report"
        submit-method="put"
        :submit-route="
            $route('reports.run', ['customer', 'customer-summary-report'])
        "
        :initial-values="initValues"
        :validation-schema="schema"
    >
        <div class="flex justify-center">
            <div>
                <SwitchInput
                    id="all-customers"
                    name="all_customers"
                    label="Show All Customers"
                    @change="
                        emit('showList', form?.getFieldValue('all_customers'))
                    "
                />
            </div>
        </div>
        <TextInput type="hidden" id="customer-list" name="customer_list" />
        <ResourceList :list="customerList" label-field="name" empty-text="">
            <template #actions="{ item }">
                <DeleteBadge icon="circle-xmark" @click="removeCust(item)" />
            </template>
        </ResourceList>
    </VueForm>
</template>
