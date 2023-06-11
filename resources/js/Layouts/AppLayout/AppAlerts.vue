<template>
    <div v-if="Object.keys(errors).length" class="alert-wrapper">
        <div v-for="error in errors" class="alert alert-danger text-center">
            <div v-if="typeof error === 'object'">
                <div v-for="err in error">
                    {{ err }}
                </div>
            </div>
            <div v-else>
                {{ error }}
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const page: pageData = usePage();
const errors = computed(() => page.props.errors);
</script>
