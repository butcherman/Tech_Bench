<script setup lang="ts">
import CustomerSearchForm from "@/Forms/Customer/CustomerSearchForm.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import ResourceList from "@/Components/_Base/ResourceList.vue";
import SwitchInput from "@/Forms/_Base/SwitchInput.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { array, boolean, object } from "yup";
import { ref, useTemplateRef, watch } from "vue";
import { searchResults } from "@/Composables/Customer/CustomerSearch.module";

const form = useTemplateRef("report-form");

/**
 * If the search customer form is shown or not
 */
const showSearch = ref<boolean>(false);
const toggleSearch = (toggle: boolean) => {
    showSearch.value = !toggle;
    if (toggle) {
        customerList.value = [];
    }
};

/**
 * List of Customers that will be in the report data
 */
const customerList = ref<customer[]>([]);
watch(customerList.value, (newList) =>
    form.value?.setFieldValue("customer_list", newList)
);

/**
 * Add a customer to the report data
 */
const addCustomer = (customer: customer): void => {
    customerList.value.push(customer);
};

/**
 * Remove a customer from the report data
 */
const removeCustomer = (customer: customer): void => {
    let index = customerList.value.indexOf(customer);
    customerList.value.splice(index, 1);
};

/*
|-------------------------------------------------------------------------------
| Vee Validate
|-------------------------------------------------------------------------------
*/
const initValues = {
    all_customers: true,
    customer_list: customerList.value,
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
            $route('reports.run', ['customers', 'customer-summary-report'])
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
                    @change="toggleSearch(form?.getFieldValue('all_customers'))"
                />
            </div>
        </div>
        <TextInput type="hidden" id="customer-list" name="customer_list" />
        <ResourceList :list="customerList" label-field="name" empty-text="">
            <template #actions="{ item }">
                <DeleteBadge
                    icon="circle-xmark"
                    @click="removeCustomer(item)"
                />
            </template>
        </ResourceList>
        <template #after-form>
            <div v-if="showSearch" class="tb-gap-y">
                <CustomerSearchForm />
                <ResourceList
                    v-if="searchResults.length"
                    :list="searchResults"
                    class="mt-4"
                    compact
                >
                    <template #list-item="{ item }">
                        <div
                            class="text-center pointer"
                            @click="addCustomer(item)"
                        >
                            {{ item.name }}
                        </div>
                    </template>
                </ResourceList>
            </div>
        </template>
    </VueForm>
</template>
