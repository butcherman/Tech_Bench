<script setup lang="ts">
import TableStacked from "@/Components/_Base/TableStacked.vue";

const tableData = {
    site_url: `Enter the full URL to access this site. This URL will be used in
        email notifications and other links.`,
    timezone: `Select your local timezone`,
    maximum_uploaded_file_size: `This is the maximum size that a single file can
        be safely uploaded to the Tech Bench. Anything over this size will be
        rejected.`,
    company_name: `Enter your company name as it should appear to visitors.`,
    welcome_message: `*Optional* Enter a welcome message that should display on
        the login page for visitors.`,
};
</script>

<template>
    <TableStacked :items="tableData" />
</template>
