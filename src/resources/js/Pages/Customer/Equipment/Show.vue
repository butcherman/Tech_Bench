<template>
    <div id="customer-wrapper">
        <Head title="Customer Equipment" />
        <div class="border-bottom border-secondary-subtle mb-2">
            <CustomerDetails />
        </div>
        <CustomerAlerts />
        <div class="row justify-content-center">
            <div class="col-md-10">
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
import CustomerAlerts from "@/Components/Customer/CustomerAlerts.vue";
import CustomerEquipmentData from "@/Components/Customer/CustomerEquipmentData.vue";
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{
    equipment: customerEquipment;
    equipmentData: customerEquipmentData[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
