<template>
    <div>
        <Head :title="customer.name" />
        <div class="border-bottom border-secondary-subtle mb-2">
            <CustomerDetails />
        </div>
        <CustomerAlerts />
        <div class="row justify-content-center">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <CustomerNoteForm
                            :customer="customer"
                            :site-list="siteList"
                            :equip-list="equipmentList"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import CustomerDetails from "@/Components/Customer/CustomerDetails.vue";
import CustomerAlerts from "@/Components/Customer/CustomerAlerts.vue";
import CustomerNoteForm from "@/Forms/Customer/CustomerNoteForm.vue";
import { ref, reactive, onMounted } from "vue";
import {
    customer,
    siteList,
    currentSite,
    permissions,
    equipmentList,
} from "@/State/CustomerState";

const props = defineProps<{}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
s
