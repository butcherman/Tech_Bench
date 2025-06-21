<script setup lang="ts">
import TableStacked from "@/Components/_Base/TableStacked.vue";

const tableData = {
    parent_customer_name: `Name of primary customer site will be created under.`,
    site_name: `Name to reference the site by`,
    address: `Physical Address of the site`,
    city: `City site is located in`,
    state: `State site is located in`,
    zip_code: `Zip Code for customer site`,
};
</script>

<template>
    <TableStacked :items="tableData" />
</template>
