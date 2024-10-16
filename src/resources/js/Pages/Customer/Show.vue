<template>
    <div id="customer-wrapper">
        <Head :title="customer.name" />
        <div class="border-bottom border-secondary-subtle mb-2">
            <CustomerManagement v-if="showManagement" class="float-end" />
            <h3 class="float-start me-2">
                <BookmarkItem
                    :is-bookmark="isFav"
                    :toggle-route="$route('customers.bookmark', customer.slug)"
                />
            </h3>
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
        <div id="notes" class="row my-2">
            <div class="col my-2">
                <CustomerNote />
            </div>
        </div>
        <div id="files" class="row my-2">
            <div class="col my-2">
                <CustomerFile />
            </div>
        </div>
        <Modal title="ALERT" ref="customerAlertModal" no-close-on-click>
            <div>{{ customerAlertMessage }}</div>
            <a
                v-if="customerAlertLink"
                :href="customerAlertLink.link"
                class="btn btn-info w-100"
            >
                {{ customerAlertLink.text }}
            </a>
        </Modal>
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
import CustomerNote from "@/Components/Customer/CustomerNote.vue";
import CustomerFile from "@/Components/Customer/CustomerFile.vue";
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import BookmarkItem from "@/Components/_Base/BookmarkItem.vue";
import Modal from "@/Components/_Base/Modal.vue";
import { computed, onMounted, onUnmounted } from "vue";
import { customer, permissions } from "@/State/CustomerState";
import {
    registerCustomerChannel,
    customerAlertModal,
    customerAlertMessage,
    customerAlertLink,
} from "@/Modules/CustomerBroadcasting.module";

defineProps<{
    isFav: boolean;
}>();

const showManagement = computed(
    () =>
        permissions.value.details.update ||
        permissions.value.details.manage ||
        permissions.value.details.delete
);

/**
 * Register to Customer Channel
 */
onMounted(() => {
    registerCustomerChannel(customer.value.slug);
});

/**
 * Leave Customer Channel
 */
onUnmounted(() => Echo.leave(`customer.${customer.value.slug}`));

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
