<script setup lang="ts">
import { InputGroup, InputGroupAddon, InputText } from "primevue";
import { ref } from "vue";
import {
    searchParams,
    triggerSearch,
    resetSearch,
} from "@/Composables/Customer/CustomerSearch.module";

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

<template>
    <form @submit.prevent="triggerSearch">
        <InputGroup>
            <InputText
                v-model="searchParams.searchFor"
                class="border px-2"
                placeholder="Search for Customer Name, Site Name or ID"
                @input="checkToSearch"
            />
            <InputGroupAddon class="bg-blue-400 text-white">
                <button type="submit" class="btn btn-info">
                    <fa-icon icon="magnifying-glass" />
                    <span class="d-none d-md-inline"> Search </span>
                </button>
            </InputGroupAddon>
            <InputGroupAddon class="bg-yellow-500 text-white">
                <button
                    v-if="!hideReset"
                    type="reset"
                    class="btn btn-warning"
                    @click="onReset"
                >
                    <fa-icon icon="rotate" />
                    <span class="d-none d-md-inline"> Reset </span>
                </button>
            </InputGroupAddon>
        </InputGroup>
    </form>
</template>
