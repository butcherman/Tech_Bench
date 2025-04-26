<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import Card from "@/Components/_Base/Card.vue";
import CustomerSearchForm from "@/Forms/Customer/CustomerSearchForm.vue";
import Modal from "@/Components/_Base/Modal.vue";
import Overlay from "@/Components/_Base/Loaders/Overlay.vue";
import ResourceList from "@/Components/_Base/ResourceList.vue";
import verifyModal from "@/Modules/verifyModal";
import { ref, onMounted, useTemplateRef } from "vue";
import { useForm } from "@inertiajs/vue3";
import {
    searchResults,
    isLoading,
    isDirty,
    setPerPageDefault,
    resetSearch,
} from "@/Composables/Customer/CustomerSearch.module";

onMounted(() => setPerPageDefault(10));

const siteModal = useTemplateRef("select-site-modal");

const fromCustomer = ref<customer | null>(null);
const toCustomer = ref<customer | null>(null);
const selectedSite = ref<customerSite | null>(null);

/**
 * Select which Customer Account to move from or to
 */
const onCustomerSelected = (event: MouseEvent, customer: customer): void => {
    if (event.isTrusted) {
        if (!fromCustomer.value) {
            fromCustomer.value = customer;

            if (customer.site_count > 1) {
                console.log("select site");
                siteModal.value?.show();
            } else {
                selectedSite.value = customer.customer_site[0];
            }
        } else {
            toCustomer.value = customer;
        }

        resetSearch();
    }
};

/**
 * Select which site to move from the customer
 */
const onSiteSelected = (event: MouseEvent, site: customerSite): void => {
    if (event.isTrusted) {
        selectedSite.value = site;

        siteModal.value?.hide();
    }
};

/**
 * Reset the page parameters.
 */
const onReset = (): void => {
    fromCustomer.value = null;
    toCustomer.value = null;
    selectedSite.value = null;

    resetSearch();
};

/**
 * Verify and then move the customer site to the proper customer
 */
const onVerify = (): void => {
    console.log("verify");

    verifyModal(
        `Equipment, Contacts, Notes and Files that are shared with other sites
         WILL NOT be moved with this site. Continue?`,
        "IMPORTANT NOTE"
    ).then((res) => {
        if (res) {
            console.log("do it");
            const formData = useForm({
                moveSiteId: selectedSite.value?.cust_site_id,
                toCustomer: toCustomer.value?.cust_id,
            });

            formData.put(route("customers.re-assign.update"), {
                onSuccess: () => onReset(),
            });
        }
    });
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div>
        <div class="flex justify-center">
            <Card class="tb-card">
                <p class="text-center">
                    Use this page to move a customer site from one customer to
                    another.
                </p>
                <p class="text-center">
                    This is useful if a site was created under the wrong
                    customer, or a new customer was created when it should be
                    been a site instead.
                </p>
            </Card>
        </div>
        <div class="flex justify-center">
            <Card class="tb-card">
                <CustomerSearchForm hide-reset />
                <Overlay :loading="isLoading">
                    <ResourceList
                        v-if="isDirty"
                        class="my-2"
                        label-field="name"
                        :list="searchResults"
                        compact
                        hover-row
                        @row-clicked="onCustomerSelected"
                    />
                </Overlay>
            </Card>
        </div>
        <div class="flex justify-center">
            <Card class="tb-card">
                <h5 v-if="!fromCustomer" class="text-center">
                    Search for customer to move site from.
                </h5>
                <div v-else class="grid grid-cols-2">
                    <div class="text-center border-e-2">
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
                        <span v-else>
                            Search for customer to move site to.
                        </span>
                    </div>
                </div>
            </Card>
        </div>
        <div class="flex justify-center">
            <Card class="tb-card">
                <div class="text-center">
                    <BaseButton
                        v-if="toCustomer"
                        text="Move Site to Customer"
                        class="w-3/4 my-2"
                        variant="danger"
                        @click="onVerify"
                    />
                    <BaseButton
                        text="Cancel and Start Over"
                        class="w-3/4 my-2"
                        variant="warning"
                        @click="onReset"
                    />
                </div>
            </Card>
        </div>
        <Modal ref="select-site-modal" title="Select Site to Move">
            <ResourceList
                v-if="fromCustomer"
                label-field="site_name"
                :list="fromCustomer?.customer_site"
                compact
                hover-row
                @row-clicked="onSiteSelected"
            />
        </Modal>
    </div>
</template>
