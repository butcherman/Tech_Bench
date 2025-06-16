<script setup lang="ts">
import TableStacked from "@/Components/_Base/TableStacked.vue";

const tableData = {
    customer_id: `Enter the Unique ID of the customer`,
    customer_name: `Unique name to identify the customer`,
    dba_name: `If the customer goes by more than one name, enter additional
        names here. This field can be used to hold additional strings to search
        for this user.`,
    address: `Physical Address of the customers primary site`,
    city: `City site is located in`,
    state: `State site is located in`,
    zip_code: `Zip Code for customer site`,
};
</script>

<template>
    <TableStacked :items="tableData" />
</template>
