<script setup lang="ts">
import { Deferred } from "@inertiajs/vue3";
import AtomLoader from "./AtomLoader.vue";

const props = defineProps<{
    data: string | string[];
    loadingText?: string;
}>();
</script>

<template>
    <Deferred :data="data">
        <template #fallback>
            <div class="flex justify-center">
                <AtomLoader :text="loadingText" />
            </div>
        </template>
        <template #rescue>
            <div class="flex flex-col justify-center">
                <div class="mx-auto">
                    <img src="/images/error/oops.png" class="max-w-75" />
                </div>
                <h4 class="text-center text-danger">Error Loading Data</h4>
            </div>
        </template>
        <slot />
    </Deferred>
</template>
