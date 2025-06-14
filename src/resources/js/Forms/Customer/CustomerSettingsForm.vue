<script setup lang="ts">
import Collapse from "@/Components/_Base/Collapse.vue";
import SelectInput from "@/Forms/_Base/SelectInput.vue";
import SwitchInput from "@/Forms/_Base/SwitchInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { boolean, object, string } from "yup";
import { allStates } from "@/Composables/allStates.module";
import { ref } from "vue";

const props = defineProps<{
    select_id: boolean;
    update_slug: boolean;
    default_state: string;
    auto_purge: boolean;
    allow_vpn_data: boolean;
    allow_share_vpn_data: boolean;
}>();

const vpnAllowed = ref<boolean>(props.allow_vpn_data);

/*
|-------------------------------------------------------------------------------
| Validation
|-------------------------------------------------------------------------------
*/
const initValues = {
    select_id: props.select_id,
    update_slug: props.update_slug,
    default_state: props.default_state,
    auto_purge: props.auto_purge,
    allow_vpn_data: props.allow_vpn_data,
    allow_share_vpn_data: props.allow_share_vpn_data,
};

const schema = object({
    select_id: boolean().required(),
    update_slug: boolean().required(),
    default_state: string().required(),
    auto_purge: boolean().required(),
    allow_vpn_data: boolean().required(),
    allow_share_vpn_data: boolean().required(),
});
</script>

<template>
    <VueForm
        ref="form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('customers.settings.update')"
        submit-method="put"
        submit-text="Update Customer Settings"
    >
        <SwitchInput
            id="select_id"
            name="select_id"
            label="Allow Users To Manually Input Customer ID when Creating New Customer"
            help="When creating a customer, should the user be able to manually enter the Customer ID?"
        />
        <SwitchInput
            id="allow-vpn-data"
            name="allow_vpn_data"
            label="Allow VPN Data for Customer Profile"
            help="Create a special section to show information for connecting to customer remotely via VPN Connection"
            @change="vpnAllowed = !vpnAllowed"
        />
        <Collapse :show="vpnAllowed">
            <SwitchInput
                id="allow-share-vpn-data"
                name="allow_share_vpn_data"
                label="Allow VPN Data to be shared across Customer Profiles"
                help="In rare occasions, a single portal may be used for multiple Customer Profiles."
            />
        </Collapse>
        <SwitchInput
            id="auto_purge"
            name="auto_purge"
            label="Automatically Remove Deleted Items after 90 Days"
            help="When Customer Equipment, Contacts, Notes or Files are deleted, they can be recovered until completely removed"
        />
        <SwitchInput
            id="update_slug"
            class="mb-3"
            name="update_slug"
            label="Update Customer Link When Name is Modified"
            help="If enabled and the customers name is changed, the link used to access the customer will be updated as well"
        />
        <SelectInput
            id="default_state"
            name="default_state"
            label="Default State when Creating New Customer"
            :list="allStates"
            text-field="text"
            value-field="value"
        />
    </VueForm>
</template>
