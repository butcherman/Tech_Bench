<script setup lang="ts">
import TableStacked from "@/Components/_Base/TableStacked.vue";
import { computed } from "vue";

const props = defineProps<{
    allowCustomUrl: boolean;
}>();

const skip = computed(() => (props.allowCustomUrl ? [] : ["link_url"]));

const tableData = {
    link_name: `Enter a name to help you easily identify this File Link`,
    link_url: `Enter a custom URL Suffix or leave the default UUID string`,
    expire_date: `Select the date this link should no longer be accessible`,
    allow_visitors_to_upload_files: `Select if others should be allowed to send
        you files through this link.`,
    add_instructions: `Add any additional information that should be shown with
        the File Link.`,
};
</script>

<template>
    <TableStacked :items="tableData" :skip="skip" />
</template>
