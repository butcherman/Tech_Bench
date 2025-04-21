<script setup lang="ts">
import { customer } from "@/Composables/Customer/CustomerData.module";
</script>

<template>
    <div>
        <h1>{{ customer.name }}</h1>
        <h5 v-if="customer.dba_name" class="text-muted">
            {{ customer.dba_name }}
        </h5>
    </div>
</template>
