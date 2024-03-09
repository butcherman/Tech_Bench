<template>
    <div id="customer-wrapper">
        <Head :title="customer.name" />
        <div class="border-bottom border-secondary-subtle mb-2">
            <CustomerManagement v-if="showManagement" class="float-end" />
            <CustomerDetails />
        </div>
        <CustomerAlerts />
        <div class="row my-2">
            <QuickJump :nav-list="quickJumpList" class="col" />
        </div>
        <div id="site-list" class="row my-2">
            <div class="col">
                <CustomerSiteList>
                    <template #add-button>
                        <Link
                            v-if="permissions.details.create"
                            :href="
                                $route('customers.sites.create', customer.slug)
                            "
                        >
                            <AddButton text="Add Site" small pill />
                        </Link>
                    </template>
                </CustomerSiteList>
            </div>
        </div>
        <div class="row">
            <div id="equipment" class="col-md-7 my-2">
                <CustomerEquipment />
            </div>
            <div id="contacts" class="col-md-5 my-2">
                <CustomerContact />
            </div>
        </div>
        <div class="row my-2">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h1>Notes</h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="row my-2">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h1>Files</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import CustomerManagement from "@/Components/Customer/CustomerManagement.vue";
import CustomerDetails from "@/Components/Customer/CustomerDetails.vue";
import CustomerAlerts from "@/Components/Customer/CustomerAlerts.vue";
import QuickJump from "@/Components/_Base/QuickJump.vue";
import CustomerSiteList from "@/Components/Customer/CustomerSiteList.vue";
import CustomerEquipment from "@/Components/Customer/CustomerEquipment.vue";
import CustomerContact from "@/Components/Customer/CustomerContact.vue";
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import { computed } from "vue";
import { customer, permissions } from "@/State/CustomerState";

const showManagement = computed(
    () =>
        permissions.value.details.update ||
        permissions.value.details.manage ||
        permissions.value.details.delete
);

const quickJumpList = [
    {
        navId: "site-list",
        name: "Sites",
    },
    {
        navId: "equipment",
        name: "Equipment",
    },
    {
        navId: "contacts",
        name: "Contacts",
    },
    {
        navId: "notes",
        name: "Notes",
    },
    {
        navId: "files",
        name: "Files",
    },
];
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
