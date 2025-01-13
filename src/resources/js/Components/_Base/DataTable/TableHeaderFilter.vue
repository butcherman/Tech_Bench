<template>
    <div>
        <input
            v-model="localValue"
            type="text"
            class="border p-1 rounded-md w-full"
            @keyup="updateFilter"
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
