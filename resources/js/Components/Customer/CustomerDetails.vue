<template>
    <div>
        <h3>
            <BookmarkItem
                :is-fav="isFav"
                :toggle-route="$route('customers.bookmark')"
                :model-id="customer.cust_id"
            />
            {{ customer?.name }}
            <small
                v-if="
                    customer?.child_count !== undefined &&
                    customer.child_count > 0
                "
                class="fs-6 pointer"
                title="Show Linked Customers"
                v-tooltip
                @click="linkedCustomers(customer?.slug, customer?.name)"
            >
                <fa-icon icon="fa-link" />
            </small>
        </h3>
        <h5 v-if="customer?.dba_name">AKA - {{ customer.dba_name }}</h5>
        <h6 v-if="customer?.parent_id">
            Child Site of -
            <Link :href="$route('customers.show', customer?.parent?.slug)">
                {{ customer?.parent?.name }}
            </Link>
        </h6>
        <address>
            <div class="float-start me-1 text-muted">
                <fa-icon icon="fa-map-marked" />
            </div>
            <a
                id="addr-span"
                :href="mapUrl"
                target="_blank"
                class="float-left ml-2"
                title="Click for Map"
                v-tooltip
            >
                {{ customer?.address }}<br />
                {{ customer?.city }},
                {{ customer?.state }}
                &nbsp;{{ customer?.zip }}
            </a>
        </address>
    </div>
</template>

<script setup lang="ts">
import BookmarkItem from "@/Components/Base/BookmarkItem.vue";
import { inject, computed } from "vue";
import { linkedCustomers } from "@/Modules/linkedCustomers.module";
import { isCustFavKey, customerKey } from "@/SymbolKeys/CustomerKeys";
import type { customerType } from "@/Types";
import type { Ref } from "vue";

const $route = route;
const isFav = inject(isCustFavKey) as boolean;
const customer = inject(customerKey) as Ref<customerType>;

/**
 * Create a URL to go to Google Maps and address
 */
const mapUrl = computed<string>(() => {
    const uri = encodeURI(
        `${customer?.value.address},${customer?.value.city},${customer?.value.state}`
    );
    return `https://www.google.com/maps/search/?api=1&query=${uri}`;
});
</script>
