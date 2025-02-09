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
            Is Loading = {{ isLoading }}
            <br />
            {{ searchResults }}
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
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import { onMounted, useTemplateRef } from "vue";
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import Modal from "@/Components/_Base/Modal.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import CustomerSearchForm from "@/Forms/Customer/CustomerSearchForm.vue";
import {
    searchResults,
    isLoading,
    triggerSearch,
} from "@/Composables/Customer/CustomerSearch.module";

defineProps<{
    permissions: customerPermissions;
}>();

const addCustomerModal = useTemplateRef("add-customer-modal");

onMounted(() => triggerSearch());
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
