<template>
    <TableStacked :rows="data" title-case />
</template>

<script setup lang="ts">
import TableStacked from "@/Components/_Base/TableStacked.vue";

const data = {
    link_name: "Give a unique name for this link so you can identify it later",
    expire_date: "Links will no longer be publicly available after this date",
    allow_visitors_to_upload_files:
        "Public Guest can upload files to you when enabled",
    instructions: "Add a custom note to the individual getting this link",
};
</script>
