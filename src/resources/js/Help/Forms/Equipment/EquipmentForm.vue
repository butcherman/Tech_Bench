<script setup lang="ts">
import TableStacked from "@/Components/_Base/TableStacked.vue";

defineProps<{
    publicTips: boolean;
}>();

const tableData = {
    equipment_category: `Select the category this equipment belongs to.`,
    equipment_name: `Enter a descriptive name to assign to this equipment`,
    allow_public_tips: `Determine if visitors can search the public Knowledge
        Base for articles on this Equipment.`,
    customer_information_to_gather: `Input one or more fields for information
        that should be gathered for this equipment when assigned to a customer.
        This could be information such as login credentials, or any other important
        data that needs to be shared between technicians.`,
};
</script>

<template>
    <TableStacked :items="tableData" />
</template>
