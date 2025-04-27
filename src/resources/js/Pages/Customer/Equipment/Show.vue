<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import CustomerDetails from "@/Components/Customer/Show/CustomerDetails.vue";
import ManageEquipment from "@/Components/Customer/Show/Equipment/ManageEquipment.vue";
import Card from "@/Components/_Base/Card.vue";
import { ref, reactive, onMounted } from "vue";

// TODO - Add Page.
const props = defineProps<{
    equipment: customerEquipment;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div>
        <div class="flex gap-2 pb-2 border-b border-slate-400">
            <CustomerDetails class="grow" />
            <ManageEquipment :equipment="equipment" />
        </div>
    </div>
</template>
