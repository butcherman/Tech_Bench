<template>
    <TableStacked :rows="formData" />
</template>

<script setup lang="ts">
import TableStacked from "@/Components/_Base/TableStacked.vue";

const formData = {
    Urgent: "If the note is marked as Urgent, it will be at the top of the note list",
    "Note Category": "Select the category this note belongs to.",
    "Site List": `If this note is for multiple sites, select the sites that 
                  the note is for.`,
    Equipment: `If the file is for Equipment attached to the customer, select 
                  that equipment.`,
    "Note Details": "Use the editor to input and format the note as needed.",
};
</script>
