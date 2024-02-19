<template>
    <div id="manage-customer-dropdown" class="dropdown">
        <button
            class="btn rounded-circle dropdown-toggle"
            type="button"
            data-bs-toggle="dropdown"
        >
            <fa-icon icon="ellipsis-vertical" />
        </button>
        <ul class="dropdown-menu">
            <li>
                <Link
                    :href="$route('customers.alerts.index', customer.slug)"
                    class="dropdown-item"
                >
                    Customer Alerts
                </Link>
            </li>
            <li>
                <Link
                    :href="$route('customers.deleted-items', customer.slug)"
                    class="dropdown-item"
                >
                    Deleted Items
                </Link>
            </li>
            <li v-if="currentSite">Edit Site</li>
            <li v-if="currentSite">Disable Site</li>
            <li>
                <Link
                    :href="$route('customers.edit', customer.slug)"
                    class="dropdown-item"
                >
                    Edit Customer
                </Link>
            </li>
            <li>
                <span
                    class="dropdown-item pointer"
                    @click="showDisableModal('customer')"
                >
                    Disable Customer
                </span>
            </li>
        </ul>
        <Modal ref="disableModal" title="Please Verify">
            <div v-if="disableForm === 'customer'">
                <p class="text-center">
                    Disabling this customer means that all sites and customer
                    information will no longer be accessible.
                </p>
                <p class="text-center">
                    For logging reasons, please note why the customer is being
                    disabled.
                </p>
                <CustomerDisableForm :customer="customer" />
            </div>
        </Modal>
    </div>
</template>

<script setup lang="ts">
import Modal from "@/Components/_Base/Modal.vue";
import CustomerDisableForm from "@/Forms/Customer/CustomerDisableForm.vue";
import { ref } from "vue";
import { customer, currentSite } from "@/State/CustomerState";

const disableModal = ref<InstanceType<typeof Modal> | null>(null);
const disableForm = ref();

const showDisableModal = (type: "customer" | "site") => {
    disableForm.value = type;
    disableModal.value?.show();
};
</script>
