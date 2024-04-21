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
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
