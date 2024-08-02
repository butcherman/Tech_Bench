<template>
    <TableStacked :rows="formData" title-case />
</template>

<script setup lang="ts">
import TableStacked from "@/Components/_Base/TableStacked.vue";

const formData = {
    subject: `Enter a descriptive subject to let others know what this Tech Tip
              is about`,
    tipType: `Select one of the options here to help users searching through Tech
              Tips filter results and find your Tip easier.`,
    equipmentTypes: `Select the equipment that this Tech Tip applies to.`,
    tipDetails: `Enter the details of the Tech Tip.  Images and links can be added
                 through the menu bar.`,
    addFile: `Up to five files can be added to each Tech Tip.  To add more files,
              create the Tech Tip and then Edit it.`,
    suppressNotification: `When disabled, all users will get an email letting them
                           know that this Tech Tip is available.`,
    makeStickyTip: `Sticky Tips are important Tech Tips that show up at the top
                    of the search results.`,
    makePublic: `Public Tech Tips are made available to the General Public without
                 being forced to log in first.  Use this option with care and be
                 sure not to include any sensitive information in Public Tech Tips.`,
};
</script>
