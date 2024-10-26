<template>
    <TableStacked :rows="formData" />
</template>

<script setup lang="ts">
import TableStacked from "@/Components/_Base/TableStacked.vue";

const formData = {
    Name: "Enter the contacts full name",
    Title: "Enter the Title of Job Position of the contact",
    Email: "Enter the contacts Email Address",
    "For Sites": `Select which sites this contact is for.  Note: this field will 
                only show if the customer has multiple sites`,
    "Local Contact": "Note if this is an onsite contact, or offsite",
    "Decision Maker": "A Decision Maker can approve billable items",
    Note: "Enter other information about this contact",
    "Phone Numbers":
        "Enter all phone numbers that this contact can be reached at",
};
</script>
