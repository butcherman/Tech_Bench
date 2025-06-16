<script setup lang="ts">
import TableStacked from "@/Components/_Base/TableStacked.vue";

const tableData = {
    allow_users_to_manually_input_customer_id: `Determine if users can manually
        enter the ID Number of the customer when creating a new customer.  Note:
        the ID cannot be modified after the customer has been created.`,
    allow_vpn_data_for_customer_profile: `Create a special section to show
        information for connecting to customer remotely via VPN Connection.`,
    automatically_remove_deleted_items_after_90_days: `All deleted Customer items
        can be recovered by an Administrator. If this option is enabled, deleted
        items will be purged automatically after 90 days.`,
    update_customer_link_when_name_is_modified: `If enabled and the customers
        name is changed, the link used to access the customer will be updated
        as well.`,
    default_state_when_creating_a_new_customer: `Select the state the majority
        of your customers are located in.`,
};
</script>

<template>
    <TableStacked :items="tableData" />
</template>
