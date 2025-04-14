<script setup lang="ts">
defineProps<{
    cust: customer;
}>();

import { showSites } from "@/Composables/Customer/CustomerSearch.module";
</script>

<template>
    <td :colspan="showSites ? 1 : 2">
        <Link
            :href="$route('customers.show', cust.slug)"
            class="m-0 p-2 block hover:font-normal"
        >
            {{ cust.name }}
        </Link>
    </td>
    <td v-if="showSites">
        <table>
            <tbody>
                <tr v-for="site in cust.customer_site">
                    <td>
                        <Link
                            :href="
                                $route('customers.sites.show', [
                                    cust.slug,
                                    site.site_slug,
                                ])
                            "
                            class="m-0 p-0 ps-2 block"
                        >
                            {{ site.site_name }}
                        </Link>
                    </td>
                </tr>
            </tbody>
        </table>
    </td>
    <td class="border-s">
        <table v-if="showSites">
            <tbody>
                <tr v-for="site in cust.customer_site">
                    <td>
                        <Link
                            :href="
                                $route('customers.sites.show', [
                                    cust.slug,
                                    site.site_slug,
                                ])
                            "
                            class="m-0 p-0 ps-2 block"
                        >
                            {{ site.city }}
                        </Link>
                    </td>
                </tr>
            </tbody>
        </table>
        <template v-else>
            <Link
                :href="$route('customers.show', cust.slug)"
                class="m-0 p-2 block hover:font-light font-light text-slate-600"
            >
                {{ cust.site_count }} locations
            </Link>
        </template>
    </td>
</template>
