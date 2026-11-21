<script setup lang="ts">
import AtomLoader from "./AtomLoader.vue";
import { Deferred } from "@inertiajs/vue3";

const props = defineProps<{
    data: string | string[];
    loadingText?: string;
}>();
</script>

<template>
    <Deferred :data="data">
        <template #fallback>
            <slot name="loader">
                <div class="flex justify-center">
                    <AtomLoader :text="loadingText" />
                </div>
            </slot>
        </template>
        <template #rescue>
            <slot name="error">
                <div class="flex flex-col justify-center">
                    <div class="mx-auto">
                        <img src="/images/error/oops.png" class="max-w-75" />
                    </div>
                    <h4 class="text-center text-danger">Error Loading Data</h4>
                </div>
            </slot>
        </template>
        <slot />
    </Deferred>
</template>
