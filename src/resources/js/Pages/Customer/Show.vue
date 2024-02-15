<template>
    <div id="customer-wrapper"><Head :title="customer.name" /></div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{
    customer: customer;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
