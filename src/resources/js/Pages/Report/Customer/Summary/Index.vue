<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import CustomerSearchForm from "@/Forms/Customer/CustomerSearchForm.vue";
import CustomerSummaryReportForm from "@/Forms/Report/Customer/CustomerSummaryReportForm.vue";
import ResourceList from "@/Components/_Base/ResourceList.vue";
import { ref } from "vue";
import { searchResults } from "@/Composables/Customer/CustomerSearch.module";

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
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div>
        <div class="flex justify-center">
            <Card class="tb-card" title="Customer Summary Report">
                <CustomerSummaryReportForm
                    :customer-list="customerList"
                    @remove="removeCustomer"
                    @show-list="toggleSearch"
                />
            </Card>
        </div>

        <div v-if="showSearch" class="flex justify-center">
            <Card class="tb-card" title="Search for Customer to Add">
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
            </Card>
        </div>
    </div>
</template>
