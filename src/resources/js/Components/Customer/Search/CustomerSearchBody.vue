<template>
    <tbody>
        <tr v-for="cust in searchResults" :key="cust.cust_id">
            <template v-if="cust.customer_site.length > 1">
                <!-- <td colspan="3" :class="cellClass" class="font-bold">
                    multiple sites
                </td> -->
                <td>
                    <Link
                        :href="$route('customers.show', cust.slug)"
                        class="block"
                        :class="cellClass"
                    >
                        {{ cust.name }}
                    </Link>
                </td>
                <td colspan="2"></td>
            </template>
            <template v-else>
                <td colspan="2">
                    <Link
                        :href="$route('customers.show', cust.slug)"
                        class="block"
                        :class="cellClass"
                    >
                        {{ cust.name }}
                    </Link>
                </td>
                <td>
                    <Link
                        :href="$route('customers.show', cust.slug)"
                        class="block"
                        :class="cellClass"
                    >
                        {{ findPrimarySite(cust).city }}
                    </Link>
                </td>
            </template>
        </tr>
    </tbody>
</template>

<script setup lang="ts">
import { searchResults } from "@/Composables/Customer/CustomerSearch.module";
import { inject } from "vue";
import { findPrimarySite } from "@/Composables/Customer/customer.module";

const cellClass = inject("cellClass");
</script>
