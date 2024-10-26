<template>
    <div>
        <customerSiteSingle v-if="isSingleSite" />
        <customerSiteMultiple v-else />
    </div>
</template>

<script setup lang="ts">
import { usePage } from "@inertiajs/vue3";
import customerSiteMultiple from "../Components/customer.site.multiple.vue";
import customerSiteSingle from "../Components/customer.site.single.vue";

const isSingleSite: boolean =
    usePage<customerPageProps>().props.customer.site_count === 1;
</script>
