<template>
    <TableStacked :rows="tableData" title-case />
</template>

<script setup lang="ts">
import TableStacked from "@/Components/_Base/TableStacked.vue";
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{}>();

const tableData = {
    upload_wildcard_certificate: `If the certificate is a wildcard certificate,
                                  or includes a private key, check this box`,
    certificate: "Copy and paste the certificate text here.",
    private_key: "Copy and paste the private key here.",
    intermediate: "Copy and paste the intermediate certificate here.",
};
</script>
