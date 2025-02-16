<template>
    <div>
        <div
            v-if="permissions.details.create"
            class="w-full flex flex-row-reverse"
        >
            <AddButton
                text="Add New Customer"
                pill
                @click="addCustomerModal?.show()"
            />
        </div>
        <Card class="mt-3">
            <CustomerSearchForm />
        </Card>
        <Card class="mt-3">
            <CustomerSearchTable />
        </Card>
        <Modal ref="add-customer-modal" title="Select Which Option to Add">
            <BaseButton
                text="New Customer"
                class="w-full my-2"
                :href="$route('customers.create')"
            />
            <BaseButton
                text="Add Site to Existing Customer"
                class="w-full my-2"
                :href="$route('customers.create-site')"
            />
        </Modal>
    </div>
</template>

<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import AppLayout from "@/Layouts/App/AppLayout.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import Card from "@/Components/_Base/Card.vue";
import CustomerSearchForm from "@/Forms/Customer/CustomerSearchForm.vue";
import Modal from "@/Components/_Base/Modal.vue";
import { onMounted, useTemplateRef } from "vue";
import {
    searchResults,
    isLoading,
    triggerSearch,
} from "@/Composables/Customer/CustomerSearch.module";
import CustomerSearchTable from "@/Components/Customer/Search/CustomerSearchTable.vue";

defineProps<{
    permissions: customerPermissions;
}>();

const addCustomerModal = useTemplateRef("add-customer-modal");

onMounted(() => triggerSearch());
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
