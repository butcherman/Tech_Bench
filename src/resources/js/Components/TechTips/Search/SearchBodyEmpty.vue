<script setup lang="ts">
import { searchParams } from "@/Composables/TechTip/TipSearch.module";
</script>

<template>
    <tr class="min-h-96">
        <td colspan="3" class="flex justify-center">
            <div class="w-full md:w-72 text-center">
                <img src="/images/error/searching.png" id="empty-image" />
                Sorry, but there are no search results. Please try another
                search.
            </div>
        </td>
    </tr>
    <tr v-for="i in searchParams.perPage" :key="i">
        <td colspan="2" class="py-1" />
    </tr>
</template>
