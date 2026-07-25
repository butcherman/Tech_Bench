<script setup lang="ts">
import { computed } from "vue";

const props = defineProps<{
    width?: number | "auto";
}>();

const skelWidth = computed(() => {
    if (props.width && props.width !== "auto") {
        return props.width;
    }

    return Math.floor(Math.random() * 50) + 50;
});
</script>

<template>
    <div class="w-full animate-pulse">
        <div
            class="block h-3 bg-gray-300 rounded-full"
            :style="`width: ${skelWidth}%`"
        />
    </div>
</template>
