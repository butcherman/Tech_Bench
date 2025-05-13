<script setup lang="ts">
import { ref } from "vue";
import { InputGroup, InputGroupAddon, InputText } from "primevue";
import {
    searchParams,
    triggerSearch,
    resetSearch,
} from "@/Composables/TechTip/TipSearch.module";

const onReset = (): void => {
    resetSearch();
    triggerSearch();
};

/**
 * Delay timer to allow user to type what they are looking for
 */
const delayTimer = ref<number | undefined>();
const checkToSearch = (): void => {
    clearTimeout(delayTimer.value);
    delayTimer.value = setTimeout(() => {
        triggerSearch();
    }, 500);
};
</script>

<template>
    <form @submit.prevent="triggerSearch">
        <InputGroup>
            <InputText
                v-model="searchParams.searchFor"
                class="border px-2"
                placeholder="Search for Tech Tip"
                @input="checkToSearch"
            />
            <InputGroupAddon class="bg-blue-400 text-white">
                <button type="submit" class="btn btn-info">
                    <fa-icon icon="magnifying-glass" />
                    <span class="d-none d-md-inline"> Search </span>
                </button>
            </InputGroupAddon>
            <InputGroupAddon class="bg-yellow-500 text-white">
                <button type="reset" class="btn btn-warning" @click="onReset">
                    <fa-icon icon="rotate" />
                    <span class="d-none d-md-inline"> Reset </span>
                </button>
            </InputGroupAddon>
        </InputGroup>
    </form>
</template>
