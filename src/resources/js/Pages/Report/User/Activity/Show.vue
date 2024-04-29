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
                        <div class="card-title">
                            User Login Activity Report
                            <span class="float-end">
                                {{ startDate }} to {{ endDate }}
                            </span>
                        </div>
                        <template v-for="(data, user) in reportData">
                            <div>
                                <span class="float-end">
                                    {{ data.length }} entries
                                </span>
                                <h5>
                                    {{ user }}
                                </h5>
                            </div>
                            <Table
                                :columns="tableCols"
                                :rows="data"
                                no-results-text="No Data Found"
                            />
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import Table from "@/Components/_Base/Table.vue";
import { router } from "@inertiajs/vue3";

interface report {
    [key: string]: {
        created_at: string;
        ip_address: string;
        user_id: number;
    }[];
}

defineProps<{
    reportData: report;
    startDate: string;
    endDate: string;
}>();

const tableCols = [
    {
        label: "Date",
        field: "created_at",
    },
    {
        label: "From IP Address",
        field: "ip_address",
    },
];
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
