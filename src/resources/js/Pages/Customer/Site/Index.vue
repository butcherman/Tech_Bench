<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import AppLayout from "@/Layouts/App/AppLayout.vue";
import BookmarkItem from "@/Components/_Base/BookmarkItem.vue";
import CustomerAlerts from "@/Components/Customer/Show/CustomerAlerts.vue";
import CustomerDetails from "@/Components/Customer/Show/CustomerDetails.vue";
import CustomerManagement from "@/Components/Customer/Show/ManageCustomer.vue";
import SiteList from "@/Components/Customer/Show/SiteList.vue";
import {
    customer,
    permissions,
    isFav,
} from "@/Composables/Customer/CustomerData.module";
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div>
        <div class="flex gap-2 border-b border-slate-400">
            <h1>
                <BookmarkItem
                    :is-bookmark="isFav"
                    :toggle-route="$route('customers.bookmark', customer.slug)"
                />
            </h1>
            <CustomerDetails class="grow" />
            <div>
                <CustomerManagement />
            </div>
        </div>
        <CustomerAlerts />
        <div class="tb-card-lg mt-2">
            <SiteList>
                <template #append-title>
                    <AddButton
                        v-if="permissions.details.create"
                        text="Add Site"
                        size="small"
                        :href="$route('customers.sites.create', customer.slug)"
                        pill
                    />
                </template>
            </SiteList>
        </div>
    </div>
</template>
