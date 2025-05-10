<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import BookmarkItem from "@/Components/_Base/BookmarkItem.vue";
import CustomerAlerts from "@/Components/Customer/Show/CustomerAlerts.vue";
import CustomerContact from "@/Components/Customer/Show/Contacts/CustomerContact.vue";
import CustomerDetails from "@/Components/Customer/Show/CustomerDetails.vue";
import CustomerEquipment from "@/Components/Customer/Show/Equipment/CustomerEquipment.vue";
import CustomerFiles from "@/Components/Customer/Show/Files/CustomerFiles.vue";
import CustomerManagement from "@/Components/Customer/Show/ManageCustomer.vue";
import CustomerNotes from "@/Components/Customer/Show/Notes/CustomerNotes.vue";
import QuickJump from "@/Components/_Base/QuickJump.vue";
import { onMounted, onUnmounted } from "vue";
import { customer, isFav } from "@/Composables/Customer/CustomerData.module";
import {
    leaveCustomerChannel,
    registerCustomerChannel,
} from "@/Composables/Customer/CustomerBroadcasting.module";

/*
|-------------------------------------------------------------------------------
| Broadcasting Data
|-------------------------------------------------------------------------------
*/
const channelName = customer.value.slug;
onMounted(() => registerCustomerChannel(channelName));
onUnmounted(() => leaveCustomerChannel(channelName));

const quickJumpList = [
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
        <div class="grid lg:grid-cols-2 tb-gap-y gap-3">
            <CustomerEquipment id="equipment" />
            <CustomerContact id="contacts" />
        </div>
        <CustomerNotes id="notes" class="tb-gap-y" />
        <CustomerFiles id="files" class="tb-gap-y" />
    </div>
</template>
