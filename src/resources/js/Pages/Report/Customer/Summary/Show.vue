<template>
    <div>
        <button class="btn btn-primary mb-2" @click="router.reload">
            <fa-icon icon="arrow-left" />
            Back
        </button>
        <div class="row justify-content-center">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Customer Summary Report</div>
                        <p>
                            Total Customers - {{ reportData.total_customers }}
                        </p>
                        <hr />
                        <div
                            v-for="cust in reportData.data"
                            :key="cust.customer_id"
                        >
                            <h6>{{ cust.name }}</h6>
                            <TableStacked :rows="cust" title-case />
                            <hr />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import TableStacked from "@/Components/_Base/TableStacked.vue";
import { router } from "@inertiajs/vue3";

interface custReportData {
    total_customers: number;
    data: {
        name: string;
        customer_id: number;
        sites: number;
        equipment_assigned: number;
        notes: number;
        contacts: number;
        files: number;
    }[];
}

defineProps<{
    reportData: custReportData;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
