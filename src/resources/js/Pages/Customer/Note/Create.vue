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
                            :current-site="currentSite"
                            :equipment="equipment"
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
import {
    customer,
    siteList,
    currentSite,
    equipmentList,
} from "@/State/CustomerState";

defineProps<{
    equipment?: customerEquipment;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
s
