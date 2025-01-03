<template>
    <div v-if="errors.length" id="app-alert-wrapper" class="mx-4 text-center">
        <Message
            v-for="err in errors"
            class="my-2"
            size="large"
            severity="error"
            pt:content:class="w-full text-center block"
        >
            <span class="w-full text-center">
                <fa-icon icon="triangle-exclamation" class="float-start" />
                {{ err }}
                <fa-icon icon="triangle-exclamation" class="float-end" />
            </span>
        </Message>
    </div>
</template>

<script setup lang="ts">
import { Message } from "primevue";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

/*
|-------------------------------------------------------------------------------
| Errors Bagged by Laravel will be handled by UI.  All others will be handled
| by this component.
|-------------------------------------------------------------------------------
*/
const errors = computed(() => {
    let baseErrors = usePage().props.errors;
    let nonBaggedErrors = [];

    for (var key in baseErrors) {
        if (typeof baseErrors[key] !== "object") {
            nonBaggedErrors.push(baseErrors[key]);
        }
    }

    return nonBaggedErrors;
});
</script>

<style>
.p-message-content {
    width: 100% !important;
}
</style>
