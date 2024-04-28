<template>
    <TableStacked :rows="formData" />
</template>

<script setup lang="ts">
import TableStacked from "@/Components/_Base/TableStacked.vue";

const formData = {
    "Select ID": `Determine if users can manually enter the ID Number of the customer 
                  when creating a new customer.  Note: the ID cannot be modified after
                  the customer has been created.`,
    "Auto Purge": `All deleted Customer items can be recovered by an Administrator.  
                   If this option is enabled, deleted items will be purged automatically
                   after 90 days.`,
    "Update Customer Link": `When a customer is created, it can be accessed by visiting 
                    https://<url>/customers/customer-name.  If the customer name is modified
                    then this will determine if the link should also be updated.  Updating 
                    the link may affect users if they have bookmarked the link.`,
    "Default State": `Select the default state that will be listed for the customers 
                      address when creating a new customer.`,
};
</script>
