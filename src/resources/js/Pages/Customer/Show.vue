<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import AppLayout from "@/Layouts/App/AppLayout.vue";
import BookmarkItem from "@/Components/_Base/BookmarkItem.vue";
import CustomerAlerts from "@/Components/Customer/Show/CustomerAlerts.vue";
import CustomerContact from "@/Components/Customer/Show/Contacts/CustomerContact.vue";
import CustomerDetails from "@/Components/Customer/Show/CustomerDetails.vue";
import CustomerEquipment from "@/Components/Customer/Show/Equipment/CustomerEquipment.vue";
import CustomerManagement from "@/Components/Customer/Show/ManageCustomer.vue";
import QuickJump from "@/Components/_Base/QuickJump.vue";
import SiteList from "@/Components/Customer/Show/SiteList.vue";
import { onMounted, onUnmounted } from "vue";
import {
    registerCustomerChannel,
    leaveCustomerChannel,
} from "@/Composables/Customer/CustomerBroadcasting.module";
import {
    customer,
    isFav,
    permissions,
} from "@/Composables/Customer/CustomerData.module";

const channelName = customer.value.slug;
onMounted(() => registerCustomerChannel(channelName));
onUnmounted(() => leaveCustomerChannel(channelName));

const quickJumpList = [
    {
        navId: "site-list",
        label: "Sites",
    },
    {
        navId: "equipment",
        label: "Equipment",
    },
    {
        navId: "contacts",
        label: "Contacts",
    },
    {
        navId: "notes",
        label: "Notes",
    },
    {
        navId: "files",
        label: "Files",
    },
];
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div>
        <div class="flex gap-2 pb-2 border-b border-slate-400">
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
        <QuickJump :nav-list="quickJumpList" class="tb-gap-y" />
        <div class="tb-card-lg tb-gap-y">
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
        <div class="grid grid-cols-2 tb-gap-y gap-3">
            <CustomerEquipment />
            <CustomerContact />
        </div>
    </div>
</template>
