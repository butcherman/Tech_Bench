<template>
    <div>
        <Head title="Re-Assign Customer" />
        <div class="row justify-content-center my-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <p class="text-center">
                            Use this page to move a customer site from one
                            customer to another.
                        </p>
                        <p class="text-center">
                            This is useful if a site was created under the wrong
                            customer, or a new customer was created when it
                            should be been a site instead.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center my-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Search Customer</div>
                        <CustomerSearchForm hide-reset />
                        <Overlay :loading="isLoading">
                            <Table
                                v-if="isDirty"
                                :rows="searchResults"
                                :columns="tableColumns"
                                :loading="isLoading"
                                no-results-text="Customer Not Found"
                                paginate
                                row-clickable
                                @onRowClick="onRowClick"
                            />
                        </Overlay>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center my-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h5 v-if="!fromCustomer" class="text-center">
                            Search for customer to move site from
                        </h5>
                        <div v-if="fromCustomer" class="row">
                            <div class="col border-end text-center">
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
                            <div class="col text-center">
                                To Customer:
                                <br />
                                <strong v-if="toCustomer">
                                    {{ toCustomer.name }}
                                </strong>
                                <span v-else>
                                    Search for customer to move site to.
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="selectedSite" class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <button
                                v-if="toCustomer"
                                class="btn btn-danger w-75 my-1"
                                @click="verifyMove"
                            >
                                Verify
                            </button>
                            <button
                                class="btn btn-warning w-75 my-1"
                                @click="resetAll"
                            >
                                Cancel and Start Over
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Modal ref="selectSiteModal" title="Select Customer Site">
            <h5 class="text-center">Select Which Site to Move</h5>
            <ul class="list-group">
                <li
                    v-for="site in fromCustomer?.customer_site"
                    class="list-group-item pointer"
                    @click="assignSite(site)"
                >
                    {{ site.site_name }}
                </li>
            </ul>
        </Modal>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import CustomerSearchForm from "@/Forms/Customer/CustomerSearchForm.vue";
import Table from "@/Components/_Base/Table.vue";
import Overlay from "@/Components/_Base/Loaders/Overlay.vue";
import Modal from "@/Components/_Base/Modal.vue";
import { ref } from "vue";
import {
    isLoading,
    searchResults,
    isDirty,
    resetSearch,
} from "@/Modules/CustomerSearch.module";
import verifyModal from "@/Modules/verifyModal";
import { useForm } from "@inertiajs/vue3";

const selectSiteModal = ref<InstanceType<typeof Modal> | null>(null);

const onRowClick = (rowData: customer) => {
    console.log(rowData.customer_site.length);

    if (fromCustomer.value && selectedSite.value) {
        toCustomer.value = rowData;
    } else {
        fromCustomer.value = rowData;
        if (rowData.customer_site.length > 1) {
            selectSiteModal.value?.show();
        } else {
            selectedSite.value = rowData.customer_site[0];
        }
    }

    resetSearch();
};

const resetAll = () => {
    fromCustomer.value = null;
    toCustomer.value = null;
    selectedSite.value = null;
    resetSearch();
};

const verifyMove = () => {
    verifyModal(
        `Equipment, Contacts, Notes and Files that are shared with other sites 
         WILL NOT be moved with this site. Continue?`,
        "IMPORTANT NOTE"
    ).then((res) => {
        if (res) {
            console.log("verified");

            const formData = useForm({
                moveSiteId: selectedSite.value?.cust_site_id,
                toCustomer: toCustomer.value?.cust_id,
            });

            formData.put(route("customers.re-assign.update"), {
                onSuccess: () => resetAll(),
            });
        }
    });
};

const assignSite = (site: customerSite) => {
    selectedSite.value = site;
    selectSiteModal.value?.hide();
};

const fromCustomer = ref<customer | null>(null);
const toCustomer = ref<customer | null>(null);
const selectedSite = ref<customerSite | null>(null);

const tableColumns = [
    {
        label: "Name",
        field: "name",
    },
];
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
