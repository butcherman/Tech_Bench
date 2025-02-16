<template>
    <tbody>
        <tr v-for="i in searchParams.perPage" :key="i">
            <td colspan="2" :class="cellClass">
                <Skeleton :width="`${Math.floor(Math.random() * 50) + 50}%`" />
            </td>
            <td :class="cellClass">
                <Skeleton :width="`${Math.floor(Math.random() * 50) + 50}%`" />
            </td>
        </tr>
    </tbody>
</template>

<script setup lang="ts">
import { Skeleton } from "primevue";
import { searchParams } from "@/Composables/Customer/CustomerSearch.module";
import { inject } from "vue";

const cellClass = inject("cellClass");
</script>
