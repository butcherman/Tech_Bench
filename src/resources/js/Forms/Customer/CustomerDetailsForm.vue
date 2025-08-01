<script setup lang="ts">
import SelectInput from "../_Base/SelectInput.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { object, string, number } from "yup";
import { computed } from "vue";
import { allStates } from "@/Composables/allStates.module";
import { checkCustId } from "@/Composables/Customer/CheckCustomerId.module";

const props = defineProps<{
    selectId: boolean;
    defaultState: string;
    customer?: customer;
    siteList?: customerSite[];
}>();

/*
|-------------------------------------------------------------------------------
| Handle Form
|-------------------------------------------------------------------------------
*/
const submitText = computed(() =>
    props.customer ? "Update Customer" : "Create Customer"
);
const submitMethod = computed(() => (props.customer ? "put" : "post"));
const submitRoute = computed(() =>
    props.customer
        ? route("customers.update", props.customer.slug)
        : route("customers.store")
);

/*
|-------------------------------------------------------------------------------
| Vee Validate
|-------------------------------------------------------------------------------
*/
const initValues = {
    cust_id: props.customer?.cust_id,
    name: props.customer?.name,
    dba_name: props.customer?.dba_name,
    primary_site_id: props.customer?.primary_site_id,
    address: null,
    city: null,
    state: props.defaultState,
    zip: null,
};

const schema = computed(() => (props.customer ? editSchema : newSchema));

const newSchema = object({
    cust_id: string()
        .nullable()
        .test("uniqueCustId", async function (value) {
            let usedData = await checkCustId(value).then((res) => res);
            if (usedData.in_use) {
                if (usedData.disabled) {
                    return this.createError({
                        message: `This Customer ID is taken by disabled customer ${usedData.cust_name}`,
                    });
                }
                return this.createError({
                    message: `This Customer ID is taken by <a href="${usedData.route}" class="text-pink-400">${usedData.cust_name}</a>`,
                });
            }
            return true;
        }),
    name: string().required().label("Customer Name"),
    dba_name: string().nullable(),
    address: string().required(),
    city: string().required(),
    state: string().required(),
    zip: number().required(),
});

const editSchema = object({
    name: string().required().label("Customer Name"),
    dba_name: string().nullable(),
    primary_site_id: number().required(),
});
</script>

<template>
    <VueForm
        :initial-values="initValues"
        :submit-method="submitMethod"
        :submit-route="submitRoute"
        :submit-text="submitText"
        :validation-schema="schema"
    >
        <TextInput
            v-if="!customer && selectId"
            id="cust-id"
            type="number"
            name="cust_id"
            label="Customer ID"
            help="If necessary, manually enter the Customers ID number to match billing and other databases"
        />
        <TextInput id="name" name="name" label="Customer Name" focus />
        <TextInput
            id="dba-name"
            name="dba_name"
            label="Secondary Name (AKA Name)"
        />
        <fieldset v-if="customer && siteList && siteList.length > 1">
            <SelectInput
                id="primary-site"
                name="primary_site_id"
                label="Primary Site"
                :list="siteList"
                text-field="site_name"
                value-field="cust_site_id"
            />
        </fieldset>
        <fieldset v-if="!customer">
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
    </VueForm>
</template>
