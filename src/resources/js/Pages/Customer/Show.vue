<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import BookmarkItem from "@/Components/_Base/BookmarkItem.vue";
import CustomerDetails from "@/Components/Customer/Show/CustomerDetails.vue";
import CustomerManagement from "@/Components/Customer/Show/CustomerManagement.vue";
import {
    customer,
    isFav,
    permissions,
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
                <CustomerManagement v-if="permissions.details.manage" />
            </div>
        </div>
        <div class="my-3" style="border: 1px solid red">
            Additional customer stuff
        </div>
    </div>
</template>
