<template>
    <tbody>
        <tr>
            <td v-if="!searchResults.length" colspan="3" class="text-center">
                No Results Found
            </td>
        </tr>
        <tr v-for="customer in searchResults" :key="customer.cust_id">
            <td>
                <Link :href="$route('customers.show', customer.slug)">
                    {{ customer.name }}
                    <span v-if="customer.dba_name">
                        - ( AKA: {{ customer.dba_name }} )
                    </span>
                </Link>
            </td>
            <td>
                <Link :href="$route('customers.show', customer.slug)">
                    {{ customer.city }}
                </Link>
            </td>
            <td>
                <Link :href="$route('customers.show', customer.slug)">
                    <template v-if="customer.customer_equipment.length > 0">
                        <div v-for="equip in customer.customer_equipment">
                            {{ equip.name }}
                        </div>
                    </template>
                    <template v-if="customer.parent_equipment.length > 0">
                        <div v-for="equip in customer.parent_equipment">
                            <fa-icon icon="share" />
                            {{ equip.name }}
                        </div>
                    </template>
                </Link>
            </td>
        </tr>
    </tbody>
</template>

<script setup lang="ts">
import { searchResults } from "@/State/Customer/SearchState";

const $route = route;
</script>

<style scoped lang="scss">
tbody {
    tr {
        td {
            padding: 0;
            height: 100%;
            a {
                display: block;
                height: 100%;
                padding: 8px 0;
                text-decoration: none;
                color: #000000;
            }
        }
    }
}
</style>
