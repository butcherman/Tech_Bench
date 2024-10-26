<template>
    <div class="text-center">
        <h5>Customer Summary Report</h5>
        <p>
            This report is a quick at a glance view of customers and their
            stored data.
        </p>
        <p>
            Each customer is listed along with basic information such as how
            many sites, notes, contacts and files are assigned to this customer.
        </p>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{}>();
</script>
