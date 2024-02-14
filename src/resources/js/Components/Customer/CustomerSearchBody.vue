import { searchResults } from '../../Modules/CustomerSearch.module';
<template>
    <tbody>
        <tr v-for="res in searchResults" :key="res.cust_id" class="row-link">
            <template v-if="res.customer_site.length > 1 && showSiteList">
                <td>
                    <Link
                        :href="$route('customers.show', res.slug)"
                        class="block-link"
                        title="View Customer"
                        v-tooltip
                    >
                        {{ res.name }}
                    </Link>
                </td>
                <td>
                    <table class="w-100 border-start">
                        <tbody>
                            <tr
                                v-for="site in sortCustSites(
                                    res.customer_site,
                                    res.primary_site_id
                                )"
                                :key="site.cust_site_id"
                            >
                                <td>
                                    <Link
                                        :href="
                                            $route(
                                                'customers.sites.show',
                                                site.site_slug
                                            )
                                        "
                                        class="block-link"
                                        title="View Site"
                                        v-tooltip
                                    >
                                        {{ site.site_name }}
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td>
                    <table class="w-100">
                        <tbody>
                            <tr
                                v-for="site in sortCustSites(
                                    res.customer_site,
                                    res.primary_site_id
                                )"
                                :key="site.cust_site_id"
                            >
                                <td>
                                    <Link
                                        :href="
                                            $route(
                                                'customers.sites.show',
                                                site.site_slug
                                            )
                                        "
                                        class="block-link"
                                        title="View Site"
                                        v-tooltip
                                    >
                                        {{ site.city }}
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </template>
            <template v-else>
                <td colspan="2">
                    <Link
                        :href="$route('customers.show', res.slug)"
                        class="block-link"
                        title="View Customer"
                        v-tooltip
                    >
                        {{ res.name }}
                    </Link>
                </td>
                <td>
                    <Link
                        :href="$route('customers.show', res.slug)"
                        class="block-link"
                        title="View Customer"
                        v-tooltip
                    >
                        {{ findPrimarySite(res)?.city }}
                    </Link>
                </td>
            </template>
        </tr>
    </tbody>
</template>

<script setup lang="ts">
import { searchResults, showSiteList } from "@/Modules/CustomerSearch.module";

const findPrimarySite = (customer: customer) => {
    return customer.customer_site.find(
        (cust) => cust.cust_id === customer.primary_site_id
    );
};

const sortCustSites = (siteList: customerSite[], primaryId: number) => {
    return siteList.sort((x) => (x.cust_site_id === primaryId ? -1 : 1));
};
</script>

<style scoped lang="scss">
tr.row-link {
    padding: 0;
    margin: 0;
    td {
        height: 100%;
        a.block-link {
            display: block;
            padding: 0.5rem;
            margin: 0;
            height: 100%;
        }
    }
}
</style>
