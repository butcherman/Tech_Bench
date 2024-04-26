<template>
    <TableStacked :rows="formData" />
</template>

<script setup lang="ts">
import TableStacked from "@/Components/_Base/TableStacked.vue";

const formData = {
    Name: "Enter a name to reference this file by.",
    "File Category": "Select the category this file belongs to.",
    "Site List": `If this file is for multiple sites, select the equipment that 
                  the file is for.`,
    Equipment: `If the file is for Equipment attached to the customer, select 
                  that equipment.`,
    "File Type": "Select the type of file to help it be easily identified.",
};
</script>
