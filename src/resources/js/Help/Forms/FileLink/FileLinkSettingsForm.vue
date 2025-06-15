<script setup lang="ts">
import TableStacked from "@/Components/_Base/TableStacked.vue";

const tableData = {
    feature_enabled: `File Link Feature can be disabled here.`,
    allow_users_to_customize_the_link_url: `When enabled, users can make a custom
        URL suffix rather than using the default UUID that is generated.`,
    auto_delete_links: `Determine if expired links should be automatically
        deleted after XX days.`,
    allow_users_to_override_auto_delete: `Individual users can have the ability
        to save their expired links longer than XX days.`,
    auto_delete_days: `How many days a link can be expired before auto deleting.`,
    default_link_life: `The default length of time a File Link is valid. Users
        can change this expiration date when creating a new link.`,
};
</script>

<template>
    <TableStacked :items="tableData" />
</template>
