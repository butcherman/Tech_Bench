<template>
    <TableStacked :rows="tableData" />
</template>

<script setup lang="ts">
import TableStacked from "@/Components/_Base/TableStacked.vue";

const tableData = {
    days_to_keep_log_files:
        "Logs older than this will be automatically deleted.",
    logging_level:
        "Select the minimum level for the logs.  Levels at this selection and above will be recorded in the log files",
};
</script>
