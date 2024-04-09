<template>
    <VueForm
        ref="form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('customers.settings.update')"
        submit-method="put"
        submit-text="Update Customer Settings"
    >
        <CheckboxSwitch
            id="select_id"
            name="select_id"
            label="Allow To Manually Input Customer ID when Creating New Customer"
            help="When creating a customer, should the user be able to manually enter the Customer ID?"
        />
        <CheckboxSwitch
            id="auto_purge"
            name="auto_purge"
            label="Automatically Remove Deleted Items after 90 Days"
            help="When Customer Equipment, Contacts, Notes or Files are deleted, they can be recovered until completely removed"
        />
        <CheckboxSwitch
            id="update_slug"
            name="update_slug"
            label="Update Customer Link When Name is Modified"
            help="If enabled and the customers name is changed, the link used to access the customer will be updated as well"
        />
        <SelectInput
            id="default_state"
            name="default_state"
            label="Default State when Creating New Customer"
            :list="allStates"
        />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import CheckboxSwitch from "../_Base/CheckboxSwitch.vue";
import SelectInput from "../_Base/SelectInput.vue";
import { boolean, object, string } from "yup";
import { allStates } from "@/Modules/AllStates.module";

const props = defineProps<{
    select_id: boolean;
    update_slug: boolean;
    default_state: string;
    auto_purge: boolean;
}>();

const initValues = {
    select_id: props.select_id,
    update_slug: props.update_slug,
    default_state: props.default_state,
    auto_purge: props.auto_purge,
};
const schema = object({
    select_id: boolean().required(),
    update_slug: boolean().required(),
    default_state: string().required(),
    auto_purge: boolean().required(),
});
</script>
