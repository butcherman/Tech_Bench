<template>
    <TableStacked :rows="tableData" title-case />
</template>

<script setup lang="ts">
import TableStacked from "@/Components/_Base/TableStacked.vue";

const tableData = {
    from_email_address: "All emails will be sent from this address.",
    "SMTP Host": "The URL of your SMTP Server",
    "SMTP Port": "The TCP port for your SMTP Server",
    encryption_method: "Type of encryption used by SMTP Server",
    require_authentication: "If authentication is required, turn this on.",
    username: "SMTP Username",
    password: "SMTP Password",
};
</script>
