<script setup lang="ts">
import { usePage } from "@inertiajs/vue3";

// TODO - Format error messages properly
const { props } = usePage();
</script>

<template>
    <div>
        <div
            v-for="err in props.errors"
            class="bg-red-600 flex py-2 px-4 rounded-lg justify-between text-white"
        >
            <div><fa-icon icon="triangle-exclamation" /></div>
            <div>{{ err }}</div>
            <div><fa-icon icon="triangle-exclamation" /></div>
        </div>
    </div>
</template>
