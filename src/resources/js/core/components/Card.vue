<script setup lang="ts">
import { computed } from "vue";

const props = defineProps<{
    title?: string;
    size?: ComponentSize;
}>();

const cardSize = computed(() => {
    return {
        sm: "w-full md:w-1/3 xl:w-1/4",
        md: "w-full lg:w-1/2 xl:w-1/3",
        lg: "w-full",
    }[props.size ?? "lg"];
});
</script>

<template>
    <div
        class="flex flex-col rounded-lg bg-white p-3 shadow-lg"
        :class="cardSize"
    >
        <div
            v-if="title || $slots.title"
            class="text-muted border-b mb-5 pb-1 flex"
        >
            <div class="grow">
                <slot name="title">
                    {{ title }}
                </slot>
            </div>
            <div>
                <span class="mb-1">
                    <slot name="append-title" />
                </span>
            </div>
        </div>
        <div class="grow">
            <slot />
        </div>
        <div v-if="$slots.footer" class="border-t mt-5 pt-1">
            <slot name="footer" />
        </div>
    </div>
</template>
