<template>
    <VueForm
        ref="form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="submitRoute"
        :submit-method="submitMethod"
        :submit-text="submitText"
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
        <fieldset v-if="customer && siteList">select parent site...</fieldset>
        <fieldset v-if="!customer">
            <TextInput id="address" name="address" label="Address" />
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
        </fieldset>
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import SelectInput from "../_Base/SelectInput.vue";
import { computed } from "vue";
import { allStates } from "@/Modules/AllStates.module";
import { object, string, number } from "yup";
import { checkCustId } from "@/Modules/CustomerIdCheck.module";

const props = defineProps<{
    selectId: boolean;
    defaultState: string;
    customer?: customer;
    siteList?: customerSite[];
}>();

const submitText = computed(() =>
    props.customer ? "Update Customer" : "Create Customer"
);
const submitMethod = computed(() => (props.customer ? "put" : "post"));
const submitRoute = computed(() =>
    props.customer
        ? route("customers.edit", props.customer.slug)
        : route("customers.store")
);

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
const schema = object({
    cust_id: string()
        .nullable()
        .test("uniqueCustId", async function (value) {
            let usedData = await checkCustId(value).then((res) => res);
            if (usedData.in_use) {
                return this.createError({
                    message: `This Customer ID is taken by <a href="${usedData.route}">${usedData.cust_name}</a>`,
                });
            }
            return true;
        }),
    name: string().required().label("Customer Name"),
    dba_name: string().nullable(),
    primary_site_id: number().when([], {
        is: props.customer === undefined,
        then: (schema) => schema.nullable(),
        otherwise: (schema) => schema.required(),
    }),
    address: string().when([], {
        is: props.customer === undefined,
        then: (schema) => schema.required(),
        otherwise: (schema) => schema.nullable(),
    }),
    city: string().when([], {
        is: props.customer === undefined,
        then: (schema) => schema.required(),
        otherwise: (schema) => schema.nullable(),
    }),
    state: string().when([], {
        is: props.customer === undefined,
        then: (schema) => schema.required(),
        otherwise: (schema) => schema.nullable(),
    }),
    zip: number().when([], {
        is: props.customer === undefined,
        then: (schema) => schema.required(),
        otherwise: (schema) => schema.nullable(),
    }),
});
</script>
