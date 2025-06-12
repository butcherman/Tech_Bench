<script setup lang="ts">
import TableStacked from "@/Components/_Base/TableStacked.vue";

const tableData = {
    password_expires_in_days: `Enter the number of days a password is valid
        before the user is forced to change it. Note:  Enter 0 to set the
        password to never expire.`,
    password_minimum_length: `Passwords must have at least this amount of
        characters in them.`,
    a_password_should_contain_at_least_one_of_the_following: `Select each item
        to customize the complexity requirements of the user password.`,
};
</script>

<template>
    <TableStacked :items="tableData" />
</template>
