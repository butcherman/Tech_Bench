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
                    :href="$route('customers.edit', customer.slug)"
                    class="dropdown-item"
                >
                    Edit Customer
                </Link>
            </li>
            <li>Edit Site</li>
            <li>Deleted Items</li>
            <li>Customer Alerts</li>
            <li>Disable Site</li>
            <li>Disable Customer</li>
        </ul>
    </div>
</template>

<script setup lang="ts">
import { customer } from "@/State/CustomerState";
// import { ref, reactive, onMounted } from "vue";
// const props = defineProps<{}>();
</script>
