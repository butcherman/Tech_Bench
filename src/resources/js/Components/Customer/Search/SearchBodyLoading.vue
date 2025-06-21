<script setup lang="ts">
import { searchParams } from "@/Composables/Customer/CustomerSearch.module";
import { Skeleton } from "primevue";
</script>

<template>
    <tr v-for="i in searchParams.perPage" :key="i">
        <td colspan="2" class="py-1">
            <Skeleton :width="`${Math.floor(Math.random() * 50) + 50}%`" />
        </td>
        <td class="py-1">
            <Skeleton :width="`${Math.floor(Math.random() * 50) + 50}%`" />
        </td>
    </tr>
</template>
