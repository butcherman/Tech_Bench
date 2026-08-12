<script setup lang="ts">
import { computed } from "vue";

const emit = defineEmits<{
    "update:perPage": [number];
}>();

const props = defineProps<{
    perPage: number;
    perPageArray: number[];
}>();

const perPageInternal = computed<number>({
    get: () => props.perPage,
    set: (value) => emit("update:perPage", Number(value)),
});
</script>

<template>
    <div>
        <select
            v-model="perPageInternal"
            class="border border-slate-300 rounded-lg p-1"
        >
            <option v-for="option in perPageArray">
                {{ option }}
            </option>
        </select>
        <span class="hidden lg:inline"> Results Per Page </span>
    </div>
</template>
