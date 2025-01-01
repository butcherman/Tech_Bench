<template>
    <form @submit.prevent="triggerSearch">
        <v-text-field
            v-model="searchParams.searchFor"
            :loading="isLoading"
            append-inner-icon="fa-magnifying-glass"
            placeholder="Search for Customer"
            variant="solo"
            hide-details
            persistent-placeholder
            @click:append-inner="triggerSearch"
        />
    </form>
</template>

<script setup lang="ts">
import { ref } from "vue";
import {
    searchParams,
    triggerSearch,
    resetSearch,
    isLoading,
} from "@/Modules/Customer/CustomerSearch.module";

defineProps<{
    hideReset?: boolean;
}>();

const onReset = () => {
    resetSearch();
    triggerSearch();
};

const delayTimer = ref<number | undefined>();
const checkToSearch = () => {
    clearTimeout(delayTimer.value);
    delayTimer.value = setTimeout(() => {
        triggerSearch();
    }, 500);
};
</script>
