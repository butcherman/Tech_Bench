<template>
    <form @submit.prevent="triggerSearch">
        <div class="input-group">
            <input
                v-model="searchParams.searchFor"
                id="search-param"
                name="search-param"
                type="text"
                placeholder="Search for Customer Name, Site Name, or ID"
                class="form-control"
                v-focus
                @input="checkToSearch"
            />
            <button type="submit" class="btn btn-info">
                <fa-icon icon="fa-brands fa-searchengin" />
                <span class="d-none d-md-inline"> Search </span>
            </button>
            <button
                v-if="!hideReset"
                type="reset"
                class="btn btn-warning"
                @click="onReset"
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
} from "@/Modules/CustomerSearch.module";

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
