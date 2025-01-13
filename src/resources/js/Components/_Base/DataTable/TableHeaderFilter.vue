<template>
    <div class="relative">
        <input
            v-model="localValue"
            type="text"
            class="border p-1 ps-2 rounded-md w-full"
            placeholder="  Filter"
            @keyup="updateFilter"
        />
        <fa-icon
            icon="filter"
            class="absolute top-1/2 transform -translate-y-1/2 right-3 text-muted"
        />
    </div>
</template>

<script setup lang="ts">
import { ref } from "vue";

const props = defineProps<{
    column: any;
    table: any;
    header: any;
}>();

const localValue = ref("");

const updateFilter = () => {
    console.log("updating filter");
    props.column.setFilterValue(localValue.value);
};
</script>
