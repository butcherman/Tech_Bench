<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import AppLayout from "@/Layouts/App/AppLayout.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import Card from "@/Components/_Base/Card.vue";
import CustomerSearchForm from "@/Forms/Customer/CustomerSearchForm.vue";
import CustomerSearchTable from "@/Components/Customer/Search/CustomerSearchTable.vue";
import Modal from "@/Components/_Base/Modal.vue";
import { onMounted, useTemplateRef } from "vue";
import { ToggleSwitch } from "primevue";
import {
    showSites,
    triggerSearch,
} from "@/Composables/Customer/CustomerSearch.module";

defineProps<{
    permissions: customerPermissions;
}>();

const modal = useTemplateRef("add-customer-modal");

onMounted(() => triggerSearch());
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div>
        <div
            v-if="permissions.details.create"
            class="w-full flex flex-row-reverse tb-gap-y"
        >
            <AddButton text="Add New Customer" pill @click="modal?.show()" />
        </div>
        <Card class="tb-gap-y">
            <CustomerSearchForm />
            <div class="flex justify-center mt-2">
                <div>
                    <ToggleSwitch input-id="show-sites" v-model="showSites" />
                    <label for="show-sites" class="align-top ms-2 text-muted">
                        Show Site List (where available)
                    </label>
                </div>
            </div>
        </Card>
        <Card class="tb-gap-y">
            <CustomerSearchTable />
        </Card>
        <Modal ref="add-customer-modal" title="Select Which Option to Add">
            <BaseButton
                text="Create New Customer"
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
