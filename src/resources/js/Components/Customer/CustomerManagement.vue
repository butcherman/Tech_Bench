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
            <li>Deleted Items</li>
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
            <li>Disable Customer</li>
        </ul>
    </div>
</template>

<script setup lang="ts">
import { customer, currentSite } from "@/State/CustomerState";
// import { ref, reactive, onMounted } from "vue";
// const props = defineProps<{}>();
</script>
