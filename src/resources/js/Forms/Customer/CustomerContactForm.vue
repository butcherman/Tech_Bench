<template>
    <VueForm
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="submitRoute"
        :submit-method="submitMethod"
        :submit-text="submitText"
    >
        <TextInput
            id="name"
            name="name"
            label="Name"
            placeholder="Contact Name"
        />
        <TextInput
            id="title"
            name="title"
            label="Title"
            placeholder="Contact Title"
        />
        <TextInput
            id="email"
            name="email"
            label="Email"
            placeholder="Contact Email Address"
            type="email"
        />
        <SelectBoxInput
            v-if="siteList.length > 1"
            id="site-list"
            name="site_list"
            label="For Sites"
            text-field="site_name"
            value-field="cust_site_id"
            :size="5"
            :list="siteList"
        />
        <div class="text-center">
            <CheckboxSwitch
                id="local"
                name="local"
                label="Local Contact"
                class="my-2"
                help="A Local Contact is on site as apposed to their primary office
                      being at another location"
                inline
            />
            <CheckboxSwitch
                id="decision-maker"
                name="decision_maker"
                label="Decision Maker"
                class="my-2"
                help="A Decision Maker can be contact when approval for changes are needed"
                inline
            />
        </div>
        <TextAreaInput
            id="note"
            name="note"
            label="Note"
            placeholder="Enter any necessary notes about this contact"
        />
        <fieldset class="mb-2">
            <legend>Phone Numbers</legend>
            <PhoneFieldArray name="phones" drag :phone-types="phoneTypes" />
        </fieldset>
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import SelectBoxInput from "../_Base/SelectBoxInput.vue";
import CheckboxSwitch from "../_Base/CheckboxSwitch.vue";
import TextAreaInput from "../_Base/TextAreaInput.vue";
import PhoneFieldArray from "../_Base/PhoneFieldArray.vue";
import { computed } from "vue";
import { array, boolean, object, string } from "yup";

const props = defineProps<{
    customer: customer;
    siteList: customerSite[];
    phoneTypes: string[];
    contact?: customerContact;
}>();

const submitRoute = computed(() =>
    props.contact
        ? route("customers.contacts.update", [
              props.customer.slug,
              props.contact.cont_id,
          ])
        : route("customers.contacts.store", props.customer.slug)
);
const submitMethod = computed(() => (props.contact ? "put" : "post"));
const submitText = computed(() =>
    props.contact ? "Update Contact" : "Create Contact"
);

const initValues = {
    name: props.contact?.name || null,
    title: props.contact?.title || null,
    email: props.contact?.email || null,
    site_list: props.contact ? [] : [],
    local: props.contact?.local || false,
    decision_maker: props.contact?.decision_maker || false,
    note: props.contact?.note || null,
    phones: props.contact
        ? []
        : [
              {
                  type: "Mobile",
                  number: "",
                  ext: "",
              },
              {
                  type: "Work",
                  number: "",
                  ext: "",
              },
          ],
};
const schema = object({
    name: string().required(),
    title: string().nullable(),
    email: string().email().nullable(),
    site_list: array().nullable(),
    local: boolean().required(),
    decision_maker: boolean().required(),
    note: string().nullable(),
    phones: array().nullable(),
});
</script>
