<template>
    <div v-if="errors" class="mx-4 text-center">
        <template v-for="err in errors">
            <v-alert
                color="error"
                border="start"
                class="font-bold"
                icon="triangle-exclamation"
            >
                {{ err }}
            </v-alert>
        </template>
    </div>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import { object } from "yup";

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
