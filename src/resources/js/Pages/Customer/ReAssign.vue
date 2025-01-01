<template>
    <div>
        <Card class="tb-card mb-4">
            <p class="text-center">
                Use this page to move a customer site from one customer to
                another.
            </p>
            <p class="text-center">
                This is useful if a site was created under the wrong customer,
                or a new customer was created when it should have been a site
                instead.
            </p>
        </Card>
        <Card class="tb-card my-4" title="Search For Customer">
            <CustomerSearchForm />
            <div v-if="isDirty" class="max-h-96 overflow-y-auto">
                <v-data-table
                    :headers="tableColumns"
                    :items="searchResults"
                    :items-per-page="-1"
                    :loading="isLoading"
                    hide-default-footer
                    @click:row="onRowClick"
                >
                    <template #bottom>
                        <div
                            v-if="paginationData.totalPages > 1"
                            class="text-center"
                        >
                            More Results Available - Input Full Name for Less
                            Results
                        </div>
                    </template>
                </v-data-table>
            </div>
        </Card>
        <Card class="tb-card my-4">
            <h5 v-if="!fromCustomer" class="text-center font-bold">
                Search for customer to move site from
            </h5>
            <div v-if="fromCustomer" class="grid md:grid-cols-2">
                <div class="text-center border-e">
                    Move Site:
                    <br />
                    <strong>
                        {{ selectedSite?.site_name }}
                    </strong>
                    <br />
                    From Customer
                    <br />
                    <strong>
                        {{ fromCustomer.name }}
                    </strong>
                </div>
                <div class="text-center">
                    To Customer:
                    <br />
                    <strong v-if="toCustomer">
                        {{ toCustomer.name }}
                    </strong>
                    <span v-else> Search for customer to move site to. </span>
                </div>
            </div>
        </Card>
        <Card
            v-if="selectedSite"
            class="w-full md:w-1/2 justify-self-center justify-items-center"
        >
            <BaseButton v-if="toCustomer" class="w-full my-2" color="red">
                Verify
            </BaseButton>
            <BaseButton class="w-full my-2" color="yellow-accent-4">
                Cancel and Start Over
            </BaseButton>
        </Card>
        <Modal ref="selectSiteModal" title="Select Customer Site">
            <h5 class="text-center font-bold border-b">
                Select Which Site to Move
            </h5>
            <v-list>
                <v-list-item
                    v-for="site in fromCustomer?.customer_site"
                    class="border-b pointer"
                    @click="assignSite(site)"
                >
                    {{ site.site_name }}
                </v-list-item>
            </v-list>
        </Modal>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import CustomerSearchForm from "@/Forms/Customer/CustomerSearchForm.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import { ref } from "vue";
import {
    searchResults,
    paginationData,
    resetSearch,
    isDirty,
    isLoading,
} from "@/Modules/Customer/CustomerSearch.module";
import Modal from "@/Components/_Base/Modal.vue";

const selectSiteModal = ref<InstanceType<typeof Modal> | null>(null);

const fromCustomer = ref<customer | null>(null);
const toCustomer = ref<customer | null>(null);
const selectedSite = ref<customerSite | null>(null);

/**
 * Handle a row clicked from the Search Bar
 */
const onRowClick = (event: MouseEvent, rowData: { item: customer }) => {
    let selectedCustomer = rowData.item;

    if (fromCustomer.value && selectedSite.value) {
        toCustomer.value = selectedCustomer;
    } else {
        fromCustomer.value = selectedCustomer;
        if (selectedCustomer.customer_site.length > 1) {
            selectSiteModal.value?.show();
            // console.log("select site");
        } else {
            selectedSite.value = selectedCustomer.customer_site[0];
        }
    }

    resetSearch();
};

/**
 * Select the site that is going to be moved
 */
const assignSite = (site: customerSite) => {
    selectedSite.value = site;
    selectSiteModal.value?.hide();
};

const tableColumns = [
    {
        title: "Name",
        value: "name",
    },
];
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
