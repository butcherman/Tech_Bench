<script setup lang="ts">
import TableStacked from "@/Components/_Base/TableStacked.vue";

const tableData = {
    role_name: `Enter a descriptive name for this Role`,
    description: `Enter a short description to describe this Roles purpose`,
    permissions: `Select each permission that the Role should have access to.
        All unchecked permissions will be blocked.`,
};
</script>

<template>
    <TableStacked :items="tableData" />
</template>
