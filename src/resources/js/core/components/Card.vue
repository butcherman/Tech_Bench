<script setup lang="ts">
import { computed } from "vue";

const props = defineProps<{
    titleIcon?: string;
    title?: string;
    subTitle?: string;
    size?: ComponentSize;
}>();

const cardSize = computed<string | undefined>(() => {
    if (props.size) {
        return {
            sm: "w-full md:w-1/3 xl:w-1/4",
            md: "w-full lg:w-1/2 xl:w-1/3",
            lg: "w-full",
        }[props.size];
    }

    return;
});

const showHeader = computed(() => {
    if (props.title || props.subTitle || props.titleIcon) {
        return true;
    }

    return false;
});
</script>

<template>
    <div
        class="flex flex-col rounded-lg bg-white p-3 shadow-lg"
        :class="cardSize"
    >
        <div
            v-if="showHeader || $slots.title || $slots['append-title']"
            class="card-title border-b border-slate-300 mb-5 pb-1 flex"
        >
            <div class="grow">
                <slot name="title">
                    <div class="flex gap-2">
                        <div v-if="titleIcon" class="flex items-center">
                            <h1 class="text-slate-600">
                                <fa-icon :icon="titleIcon" />
                            </h1>
                        </div>
                        <div>
                            <h3 v-if="title">{{ title }}</h3>
                            <p v-if="subTitle" class="text-muted">
                                {{ subTitle }}
                            </p>
                        </div>
                    </div>
                </slot>
            </div>
            <div class="flex items-center">
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
