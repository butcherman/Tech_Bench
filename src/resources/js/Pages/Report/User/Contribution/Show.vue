<template>
    <div>
        <button class="btn btn-primary mb-2" @click="router.reload">
            <fa-icon icon="arrow-left" />
            Back
        </button>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            User Contributions Report
                            <span class="float-end">
                                {{ startDate }} to {{ endDate }}
                            </span>
                        </div>
                        <template v-for="(data, user) in reportData">
                            <div>
                                <h5>
                                    {{ user }}
                                </h5>
                            </div>
                            <TableStacked :rows="data" align-left />
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import TableStacked from "@/Components/_Base/TableStacked.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { router } from "@inertiajs/vue3";

interface report {
    [key: string]: number[];
}

defineProps<{
    reportData: report;
    startDate: string;
    endDate: string;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
