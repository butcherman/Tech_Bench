<script setup lang="ts">
import { searchParams } from "@/Composables/TechTip/TipSearch.module";
import { Skeleton } from "primevue";
</script>

<template>
    <tr v-for="i in searchParams.perPage" :key="i" class="border-b">
        <td />
        <td class="py-2">
            <Skeleton :width="`${Math.floor(Math.random() * 50) + 50}%`" />
        </td>
        <td class="py-2">
            <Skeleton :width="`${Math.floor(Math.random() * 50) + 50}%`" />
        </td>
    </tr>
</template>
