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
                        <div class="card-title">Customer Files Report</div>
                        <div
                            v-for="(data, name) in reportData"
                            :key="name"
                            class="mb-2"
                        >
                            <h6>
                                Customer that {{ hasTag }} {{ name }} files:
                                <span class="float-end"
                                    >{{ data.length }} total</span
                                >
                            </h6>
                            <ul class="list-group">
                                <li
                                    v-for="cust in data"
                                    :key="cust.cust_id"
                                    class="list-group-item"
                                >
                                    {{ cust.name }}
                                </li>
                            </ul>
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
import { router } from "@inertiajs/vue3";

interface reportData {
    [key: string]: customer[];
}

defineProps<{
    reportData: reportData;
    hasTag: "have" | "are missing";
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
