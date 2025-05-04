<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import CustomerDetails from "@/Components/Customer/Show/CustomerDetails.vue";
import CustomerNotes from "@/Components/Customer/Show/Notes/CustomerNotes.vue";

defineProps<{
    equipment?: customerEquipment;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div>
        <div class="flex gap-2 pb-2 mb-2 border-b border-slate-400">
            <CustomerDetails class="grow" />
        </div>
        <CustomerNotes :equipment="equipment" />
    </div>
</template>
