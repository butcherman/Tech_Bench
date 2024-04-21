<template>
    <VueForm
        ref="customerSiteForm"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="submitRoute"
        :submit-method="submitMethod"
        :submit-text="submitText"
    >
        <TextInput
            id="cust_name"
            name="cust_name"
            label="Parent Customer Name"
            :disabled="parentCustomer ? true : false"
            @focus="searchModal?.show"
        />
        <TextInput id="name" name="site_name" label="Site Name" />
        <TextInput id="address" name="address" label="Site Address" />
        <TextInput id="city" name="city" label="City" />
        <div class="row p-0">
            <div class="col">
                <SelectInput
                    id="state"
                    name="state"
                    label="State"
                    :list="allStates"
                />
            </div>
            <div class="col">
                <TextInput
                    id="zip-code"
                    name="zip"
                    label="Zip Code"
                    type="number"
                />
            </div>
        </div>
        <Modal ref="searchModal" title="Search for Parent Customer">
            <input
                v-model="searchParam"
                type="text"
                class="form-control"
                placeholder="Enter Customer Name"
                list="customer-datalist"
                @keyup="triggerDatalistSearch"
            />
            <datalist id="customer-datalist">
                <template v-for="item in customerDatalist" :key="item">
                    <option :value="item.name" />
                </template>
            </datalist>
            <button
                type="button"
                class="btn btn-info w-100 my-2"
                :disabled="customerDatalist.length !== 1"
                @click="assignParentCustomer"
            >
                Ok
            </button>
        </Modal>
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import SelectInput from "../_Base/SelectInput.vue";
import Modal from "@/Components/_Base/Modal.vue";
import { ref, computed } from "vue";
import { allStates } from "@/Modules/AllStates.module";
import {
    customerDatalist,
    fetchDatalist,
} from "@/Modules/CustomerDatalistSearch";
import { object, string, number } from "yup";

const props = defineProps<{
    defaultState: string;
    parentCustomer: customer | null;
    customerSite?: customerSite;
}>();

const customerSiteForm = ref<InstanceType<typeof VueForm> | null>(null);

const submitRoute = computed(() => {
    if (props.parentCustomer && props.customerSite) {
        return route("customers.sites.update", [
            props.parentCustomer?.slug,
            props.customerSite.site_slug,
        ]);
    }

    if (props.parentCustomer) {
        return route("customers.sites.store", [props.parentCustomer?.slug]);
    }

    return route("customers.store-site");
});
const submitMethod = computed(() => (props.customerSite ? "put" : "post"));
const submitText = computed(() =>
    props.customerSite ? "Update Site" : "Create Site"
);

const initValues = {
    cust_name: props.parentCustomer?.name,
    cust_id: props.parentCustomer?.cust_id || null,
    site_name: props.customerSite?.site_name || null,
    address: props.customerSite?.address || null,
    city: props.customerSite?.city || null,
    state: props.customerSite?.state || props.defaultState,
    zip: props.customerSite?.zip || null,
};
const schema = object({
    cust_name: string().required(
        "Please select a Customer this site is assigned to"
    ),
    cust_id: number().required(),
    site_name: string().required().label("Site Name"),
    address: string().required(),
    city: string().required(),
    state: string().required().label("State"),
    zip: number().required(),
});

/*******************************************************************************
 * Parent Customer Search
 *******************************************************************************/
const searchModal = ref<InstanceType<typeof Modal> | null>(null);
const searchParam = ref("");
const triggerDatalistSearch = () => {
    fetchDatalist(searchParam.value);
};
const assignParentCustomer = () => {
    let parent: customer = customerDatalist.value[0];
    customerSiteForm.value?.setFieldValue("cust_name", parent.name);
    customerSiteForm.value?.setFieldValue("cust_id", parent.cust_id);

    searchModal.value?.hide();
};
</script>
