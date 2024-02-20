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
                <CustomerSiteList />
            </div>
        </div>
        <div class="row my-2">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h1>Equipment</h1>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h1>Contacts</h1>
                    </div>
                </div>
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
import { ref, computed, onMounted } from "vue";
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
        navId: "contacts",
        name: "Contacts",
    },
    {
        navId: "equipment",
        name: "Equipment",
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
