<script setup lang="ts">
import TableStacked from "@/Components/_Base/TableStacked.vue";

const tableData = {
    header: "page",
};
</script>

<template>
    <TableStacked :items="tableData" />
</template>
