<script setup lang="ts">
import TableStacked from "@/core/components/TableStacked.vue";

const tableData = {
    days_to_keep_log_files: `Log files are automatically deleted after this
        many days`,
    logging_level: `Select the minimum level for logging.`,
};
</script>

<template>
    <TableStacked :items="tableData" />
</template>
