<template>
    <TableStacked :rows="tableData" title-case />
</template>

<script setup lang="ts">
import TableStacked from "@/Components/_Base/TableStacked.vue";

const tableData = {
    "Site URL": `Enter the full URL to access this site.  This URL will be used 
                 in email notifications and other links`,
    timezone: "Select your local Timezone",
    maximum_uploaded_file_size: `This is the maximum size that a single file can
                                 be safely uploaded to the Tech Bench.  Anything 
                                 over this size will be rejected.`,
};
</script>
