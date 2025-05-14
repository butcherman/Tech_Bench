<script setup lang="ts">
import ResultWithoutSites from "./ResultWithoutSites.vue";
import ResultWithSites from "./ResultWithSites.vue";
import SearchBodyEmpty from "./SearchBodyEmpty.vue";
import SearchBodyLoading from "./SearchBodyLoading.vue";
import {
    searchResults,
    isLoading,
} from "@/Composables/Customer/CustomerSearch.module";
</script>

<template>
    <tbody>
        <SearchBodyLoading v-if="isLoading" />
        <SearchBodyEmpty v-else-if="!searchResults.length" />
        <template v-else>
            <tr
                v-for="cust in searchResults"
                :key="cust.cust_id"
                class="border-b"
            >
                <ResultWithoutSites v-if="cust.site_count === 1" :cust="cust" />
                <ResultWithSites v-else :cust="cust" />
            </tr>
        </template>
    </tbody>
</template>
