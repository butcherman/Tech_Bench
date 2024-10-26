<template>
    <form @submit.prevent="triggerPublicSearch">
        <div class="input-group">
            <input
                v-model="searchParams.searchFor"
                id="search-param"
                name="search-param"
                type="text"
                placeholder="What would you like to search for?"
                class="form-control"
                v-focus
                @input="checkToSearch"
            />
            <button type="submit" class="btn btn-info">
                <fa-icon icon="fa-brands fa-searchengin" />
                <span class="d-none d-md-inline"> Search </span>
            </button>
            <button
                type="reset"
                class="btn btn-warning"
                title="Reset All Search Parameters"
                @click="onReset"
                v-tooltip
            >
                <fa-icon icon="rotate" />
                <span class="d-none d-md-inline"> Reset </span>
            </button>
        </div>
    </form>
</template>

<script setup lang="ts">
import { ref } from "vue";
import {
    searchParams,
    triggerPublicSearch,
    resetSearch,
} from "@/Modules/TipSearch.module";

const onReset = () => {
    resetSearch();
    triggerPublicSearch();
};

const delayTimer = ref<number | undefined>();
const checkToSearch = () => {
    clearTimeout(delayTimer.value);
    delayTimer.value = setTimeout(() => {
        triggerPublicSearch();
    }, 500);
};
</script>
