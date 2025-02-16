<template>
    <div class="w-full">
        <table class="table-auto w-full">
            <thead class="border-b">
                <tr>
                    <th class="text-start">Name</th>
                    <th></th>
                    <th class="text-start">City</th>
                </tr>
            </thead>
            <CustomerSearchBodyLoading v-if="isLoading" />
            <CustomerSearchBodyEmpty v-else-if="!searchResults.length" />
            <CustomerSearchBody v-else />
        </table>
    </div>
</template>

<script setup lang="ts">
import CustomerSearchBodyEmpty from "./CustomerSearchBodyEmpty.vue";
import CustomerSearchBodyLoading from "./CustomerSearchBodyLoading.vue";
import CustomerSearchBody from "./CustomerSearchBody.vue";
import {
    searchResults,
    isLoading,
} from "@/Composables/Customer/CustomerSearch.module";
import { provide } from "vue";

const cellClass = "p-3 border-collapse border-b";

provide("cellClass", cellClass);
</script>
