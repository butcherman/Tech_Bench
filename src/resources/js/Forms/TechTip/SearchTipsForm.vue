<script setup lang="ts">
import { ref } from "vue";
import { InputGroup, InputGroupAddon, InputText } from "primevue";
import {
    searchParams,
    triggerSearch,
    resetSearch,
    triggerPublicSearch,
} from "@/Composables/TechTip/TipSearch.module";

const props = defineProps<{
    isPublic?: boolean;
    placeholder?: string;
}>();

/**
 * Clear all Search Parameters and set back to default
 */
const onReset = (): void => {
    resetSearch();
    search();
};

/**
 * Delay timer to allow user to type what they are looking for
 */
const delayTimer = ref<number | undefined>();
const checkToSearch = (): void => {
    clearTimeout(delayTimer.value);
    delayTimer.value = setTimeout(() => {
        search();
    }, 500);
};

/**
 * Determine if we are performing a public or private search.
 */
const search = () => {
    if (props.isPublic) {
        triggerPublicSearch();

        return;
    }

    triggerSearch();
};
</script>

<template>
    <form @submit.prevent="search">
        <InputGroup>
            <InputText
                v-model="searchParams.searchFor"
                class="border px-2"
                :placeholder="placeholder ?? 'Search for Tech Tip'"
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
