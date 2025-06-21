<script setup lang="ts">
import TableStacked from "@/Components/_Base/TableStacked.vue";

const tableData = {
    urgent: `If the note is marked as Urgent, it will be at the top of the note
        list`,
    note_category: "Select the category this note belongs to.",
    site_list: `If this note is for multiple sites, select the sites that the
        note is for.`,
    Equipment: `If the file is for Equipment attached to the customer, select
        that equipment.`,
    note_details: "Use the editor to input and format the note as needed.",
};
</script>

<template>
    <TableStacked :items="tableData" />
</template>
