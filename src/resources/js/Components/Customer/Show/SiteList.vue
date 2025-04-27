<script setup lang="ts">
import Card from "@/Components/_Base/Card.vue";
import ResourceList from "@/Components/_Base/ResourceList.vue";
import { customer, siteList } from "@/Composables/Customer/CustomerData.module";

defineProps<{
    title?: string;
}>();

const goToSite = (site: customerSite): string => {
    return route("customers.sites.show", [customer.value.slug, site.site_slug]);
};
</script>

<template>
    <Card :title="title ?? 'Sites'">
        <template #append-title>
            <slot name="append-title" />
        </template>
        <ResourceList
            :list="siteList"
            :link-fn="goToSite"
            paginate
            :per-page="5"
        >
            <template #list-item="{ item }">
                {{ item.site_name }} ({{ item.city }})
                <span
                    v-if="item.is_primary"
                    class="float-end text-blue-500"
                    v-tooltip.left="'Primary Site'"
                >
                    <fa-icon icon="paper-plane" />
                </span>
            </template>
        </ResourceList>
    </Card>
</template>
