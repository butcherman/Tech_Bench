<script setup lang="ts">
import PickListInput from "../_Base/PickListInput.vue";
import PhoneFieldInputArray from "../_Base/PhoneFieldInputArray.vue";
import SwitchInput from "../_Base/SwitchInput.vue";
import TextAreaInput from "../_Base/TextAreaInput.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { computed } from "vue";
import { object, string, boolean, array } from "yup";

interface phoneListItem {
    type: string;
    number: number;
    ext: string | null;
    id: number;
}

defineEmits<{
    success: [];
}>();

const props = defineProps<{
    customer: customer;
    siteList: customerSite[];
    phoneTypes: phoneType[];
    contact?: customerContact;
}>();

/**
 * Process the existing phone number entries and add them to the form state.
 */
const buildPhoneInitialValues = () => {
    let phoneList: phoneListItem[] = [];

    props.contact?.customer_contact_phone.forEach((phone) => {
        phoneList.push({
            type: phone.phone_number_type.description,
            number: phone.phone_number,
            ext: phone.extension,
            id: phone.id,
        });
    });

    return phoneList;
};

/*
|-------------------------------------------------------------------------------
| Handle Form
|-------------------------------------------------------------------------------
*/
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

/*
|-------------------------------------------------------------------------------
| Vee Validate
|-------------------------------------------------------------------------------
*/
const initValues = {
    name: props.contact?.name,
    title: props.contact?.title,
    email: props.contact?.email,
    site_list: props.contact
        ? props.contact.sites.map((site) => site.cust_site_id)
        : [],
    local: props.contact?.local ?? false,
    decision_maker: props.contact?.decision_maker ?? false,
    note: props.contact?.note,
    phones: props.contact
        ? buildPhoneInitialValues()
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

<template>
    <VueForm
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="submitRoute"
        :submit-method="submitMethod"
        :submit-text="submitText"
        @success="$emit('success')"
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
        <PickListInput
            v-if="siteList.length > 1"
            id="equipment-site-list"
            label="For Sites"
            name="site_list"
            label-field="site_name"
            value-field="cust_site_id"
            :list="customer.sites"
        />
        <div class="flex justify-center">
            <div>
                <SwitchInput
                    id="local"
                    name="local"
                    label="Local Contact"
                    help="A Local Contact is on site as apposed to their primary office
                      being at another location"
                />
                <SwitchInput
                    id="decision-maker"
                    name="decision_maker"
                    label="Decision Maker"
                    help="A Decision Maker can be contact when approval for changes are needed"
                />
            </div>
        </div>
        <TextAreaInput
            id="note"
            name="note"
            label="Note"
            placeholder="Enter any necessary notes about this contact"
        />
        <PhoneFieldInputArray
            id="phone-list"
            name="phones"
            :phone-types="phoneTypes"
            label="phone list"
            remove-warning="This may be a bad idea"
        />
    </VueForm>
</template>
