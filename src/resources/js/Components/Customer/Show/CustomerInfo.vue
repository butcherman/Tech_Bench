<script setup lang="ts">
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import {
    customer,
    currentSite,
} from "@/Composables/Customer/CustomerData.module";

const equipment = computed<customerEquipment>(
    () => usePage<customerPageProps>().props.equipment
);
</script>

<template>
    <div class="flex text-muted border-b border-b-slate-400">
        <div>Customer ID: {{ customer.cust_id }}</div>
        <div v-if="currentSite" class="mx-2 px-2 border-s border-s-slate-400">
            Site ID: {{ currentSite.cust_site_id }}
        </div>
        <div v-if="equipment" class="mx-2 px-2 border-s border-s-slate-400">
            Customer Equipment ID: {{ equipment.cust_equip_id }}
        </div>
    </div>
</template>
