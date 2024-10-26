<template>
    <form @submit.prevent="triggerSearch">
        <div class="input-group">
            <input
                v-model="searchParams.searchFor"
                id="search-param"
                name="search-param"
                type="text"
                placeholder="Search for Tech Tip"
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
    triggerSearch,
    resetSearch,
} from "@/Modules/TipSearch.module";

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
