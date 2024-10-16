<template>
    <div class="row justify-content-center">
        <div class="col">
            <CustomerSiteList>
                <template #add-button>
                    <Link
                        v-if="permissions.details.create"
                        :href="$route('customers.sites.create', customer.slug)"
                    >
                        <AddButton text="Add Site" small pill />
                    </Link>
                </template>
            </CustomerSiteList>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import CustomerSiteList from "@/Components/Customer/CustomerSiteList.vue";
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import { customer, permissions } from "@/State/CustomerState";
import { onMounted, onUnmounted } from "vue";
import { registerCustomerChannel } from "@/Modules/CustomerBroadcasting.module";

/**
 * Register to Customer Channel
 */
const channelName = customer.value.slug;
onMounted(() => {
    registerCustomerChannel(channelName);
});

/**
 * Leave Customer Channel
 */
onUnmounted(() => Echo.leave(`customer.${channelName}`));
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
