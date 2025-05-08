<script setup lang="ts">
import CustomerSearchModal from "@/Components/Customer/Search/CustomerSearchModal.vue";
import SelectInput from "../_Base/SelectInput.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { object, string, number } from "yup";
import { computed, useTemplateRef } from "vue";
import { allStates } from "@/Composables/allStates.module";

const props = defineProps<{
    defaultState: string;
    parentCustomer?: customer;
    customerSite?: customerSite;
}>();

const siteForm = useTemplateRef("customer-site-form");
const searchModal = useTemplateRef("search-modal");

const showSearch = () => {
    searchModal.value?.show();
};

const onCustomerSelected = (customer: customer) => {
    siteForm.value?.setFieldValue("cust_name", customer.name);
    siteForm.value?.setFieldValue("cust_id", customer.cust_id);
};

/*
|-------------------------------------------------------------------------------
| Handle Form
|-------------------------------------------------------------------------------
*/
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

/*
|-------------------------------------------------------------------------------
| Vee-Validate
|-------------------------------------------------------------------------------
*/
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
</script>

<template>
    <VueForm
        ref="customer-site-form"
        :initial-values="initValues"
        :submit-method="submitMethod"
        :submit-route="submitRoute"
        :submit-text="submitText"
        :validation-schema="schema"
    >
        <TextInput
            id="cust_name"
            autocomplete="off"
            name="cust_name"
            label="Parent Customer Name"
            :disabled="parentCustomer ? true : false"
            @focus="showSearch()"
        />
        <TextInput id="name" name="site_name" label="Site Name" />
        <fieldset>
            <TextInput id="address" name="address" label="Address" />
            <TextInput id="city" name="city" label="City" />
            <div class="flex">
                <div class="w-1/2 me-1">
                    <SelectInput
                        id="state"
                        name="state"
                        label="State"
                        :list="allStates"
                        text-field="text"
                        value-field="value"
                    />
                </div>
                <div class="w-1/2 ms-1">
                    <TextInput
                        id="zip-code"
                        name="zip"
                        label="Zip Code"
                        type="number"
                    />
                </div>
            </div>
        </fieldset>
        <CustomerSearchModal
            ref="search-modal"
            @selected="onCustomerSelected"
        />
    </VueForm>
</template>
