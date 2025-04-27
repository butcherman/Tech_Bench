<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import CustomerEquipment from "@/Components/Customer/Show/Equipment/CustomerEquipment.vue";
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="flex justify-center">
        <CustomerEquipment class="tb-card" />
    </div>
</template>
