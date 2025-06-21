<script setup lang="ts">
import TableStacked from "@/Components/_Base/TableStacked.vue";

const tableData = {
    require_two_factor_authentication: `Turn on 2FA`,
    allow_users_to_save_devices_for_future_login: `When selected, users can mark
        their device as "safe" so they are not required to enter a 2FA key the
        next time they log in from that device.`,
    allow_email_as_two_factor_method: `When enabled, users can have the 2FA code
        sent to their email address.`,
    allow_authenticator_app_as_two_factor_method: `When enabled, users can setup
        any valid Authenticator App on their smart device to provide the 2FA code.`,
    allow_office_365_login: `Allow users to authenticate with their Microsoft
        Azure account`,
    allow_anyone_in_my_organization_to_login: `When enabled, users within the
        organization who have not been manually created in Tech Bench can still
        authenticate and log into the Tech Bench.  When disabled, each user must
        be manually created in the Tech Bench before they will be allowed login.`,
    user_role_when_creating_new_user: `When a new user is automatically created
        in the Tech Bench, this User Role will be assigned to them at default.`,
    azure_tenant_id: `Enter your Azure Tenant ID`,
    azure_client_id: `Enter the Client ID provided by the Custom Application
        configuration in Azure.`,
    azure_client_secret: `Enter the Secret Value provided by the Custom Application
        configuration in Azure.`,
    date_client_secret_expires: `Enter the date the Customer Application Secret
        will expire so that Administrators can be properly notified before the
        expiration date. Failure to renew the secret will result in SSO being
        disabled.`,
    azure_redirect_uri: `This field is automatically generated and should be
        entered in the Custom Application configuration in Azure.`,
};
</script>

<template>
    <TableStacked :items="tableData" />
</template>
