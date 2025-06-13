<script setup lang="ts">
import TableStacked from "@/Components/_Base/TableStacked.vue";

const tableData = {
    username: `Enter a username that the user will login with.`,
    first_name: `Users First Name`,
    last_name: `Users Last Name`,
    email_address: `Email address used for notifications and recovery.`,
    role: `Select the permission level that the user should have.`,
};
</script>

<template>
    <TableStacked :items="tableData" />
</template>
