<template>
    <div>
        <h5 class="text-center">Customer Equipment</h5>
        <p class="text-center">
            Specific details about the equipment are shown here.
        </p>
        <p class="text-center">
            The first section shows detailed information about the equipment.
            Each field can be individually modified, or all at once.
        </p>
        <ul class="list-group">
            <customerSiteList is-equipment />
            <customerSiteNotes />
            <customerSiteFiles />
        </ul>
    </div>
</template>

<script setup lang="ts">
import customerSiteList from "../Components/customer.siteList.vue";
import customerSiteNotes from "../Components/customer.site.notes.vue";
import customerSiteFiles from "../Components/customer.site.files.vue";
</script>
