<template>
    <div id="customer-wrapper">
        <Head :title="customer.name" />
        <div class="border-bottom border-secondary-subtle mb-2">
            <CustomerDetails />
        </div>
        <div class="row justify-content-center">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="text-center">{{ equipment.equip_name }}</h5>
                        <CustomerEquipmentData
                            :equipment-data="equipmentData"
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
import CustomerEquipmentData from "@/Components/Customer/CustomerEquipmentData.vue";
import { customer } from "@/State/CustomerState";

defineProps<{
    equipment: customerEquipment;
    equipmentData: customerEquipmentData[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
