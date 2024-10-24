<template>
    <TableStacked :rows="formData" title-case />
</template>

<script setup lang="ts">
import TableStacked from "@/Components/_Base/TableStacked.vue";

const formData = {
    allowCommentsOnTechTips: `Determines if a user can comment on a Tech Tip.
                              Once enabled globally, Administrators can enable
                              and disable commenting based on the users Role.`,
    allowPublicTechTips: `Once enabled, users with the proper permission can
                          make a Tech Tip available to the general public.`,
};
</script>
