<script setup lang="ts">
import TableStacked from "@/Components/_Base/TableStacked.vue";

const tableData = {
    allow_comments: `Determine if other users can expand on a Tech Tip by leaving
        a comment.`,
    allow_download_tech_tip_as_pdf: `Determine whether or not a user can download
        the current Tech Tip as a PDF document`,
    allow_public_tips: `When enabled, Tech Tips can be marked as public so that
        non registered users can view them.`,
    text_for_public_tips_on_home_page: `Text used to let non registered users
        how to access the Public Tech Tips page`,
};
</script>

<template>
    <TableStacked :items="tableData" />
</template>
