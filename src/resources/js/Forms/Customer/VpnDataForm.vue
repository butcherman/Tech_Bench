<script setup lang="ts">
import Collapse from "@/Components/_Base/Collapse.vue";
import SwitchInput from "../_Base/SwitchInput.vue";
import TextAreaInput from "../_Base/TextAreaInput.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { computed, ref } from "vue";
import { object, string } from "yup";

defineEmits<{
    success: [];
}>();

const props = defineProps<{
    customer: customer;
    vpnData?: vpnData;
}>();

const showCredentials = ref<boolean>(
    props.vpnData?.vpn_username ? true : false
);

const submitRoute = computed<string>(() =>
    props.vpnData
        ? route("customers.vpn-data.update", [
              props.customer.slug,
              props.vpnData.vpn_id,
          ])
        : route("customers.vpn-data.store", props.customer.slug)
);
const submitMethod = computed<string>(() => (props.vpnData ? "put" : "post"));
const submitText = computed<string>(() =>
    props.vpnData ? "Update VPN Data" : "Create VPN Data"
);

/*
|-------------------------------------------------------------------------------
| Vee Validate
|-------------------------------------------------------------------------------
*/
const initValues = {
    vpn_client_name: props.vpnData?.vpn_client_name,
    vpn_portal_url: props.vpnData?.vpn_portal_url,
    vpn_username: props.vpnData?.vpn_username,
    vpn_password: props.vpnData?.vpn_password,
    notes: props.vpnData?.notes,
};
const schema = object({
    vpn_client_name: string().required(
        "Please enter the Client used for the VPN Connection"
    ),
    vpn_portal_url: string().required("What is the URL of the VPN Portal?"),
    vpn_username: string().nullable(),
    vpn_password: string().nullable(),
    notes: string().nullable(),
});
</script>

<template>
    <VueForm
        :initial-values="initValues"
        :submit-method="submitMethod"
        :submit-route="submitRoute"
        :submit-text="submitText"
        :validation-schema="schema"
        @success="$emit('success')"
    >
        <TextInput
            id="vpn-client-name"
            name="vpn_client_name"
            label="VPN Client"
            focus
        />
        <TextInput
            id="vpn-portal-url"
            name="vpn_portal_url"
            label="VPN Portal URL"
        />
        <div class="flex justify-center">
            <SwitchInput
                id="generic-credentials"
                name="generic_credentials"
                label="Use Generic/Shared Credentials"
                @change="showCredentials = !showCredentials"
            />
        </div>
        <Collapse :show="showCredentials">
            <TextInput
                id="vpn-username"
                name="vpn_username"
                label="VPN Username"
            />
            <TextInput
                id="vpn-password"
                name="vpn_password"
                label="VPN Password"
            />
        </Collapse>
        <TextAreaInput id="notes" name="notes" label="Notes" />
    </VueForm>
</template>
