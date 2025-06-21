<script setup lang="ts">
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const title = computed<string>(
    () => usePage<pageProps>().props.breadcrumbs.pop()?.title ?? "Help"
);

const getTitle = () => {
    return title;
};

defineExpose({ getTitle });
</script>

<template>
    <div class="flex flex-col items-center">
        <div class="md:w-3/4 text-center">
            <p>
                All customers that have been disabled are listed here. They can
                be re-activated, or completely removed along with all attached
                data.
            </p>
            <p>
                <strong class="text-danger">Important Note:</strong> Deleting a
                customer is permanent and cannot be undone.
            </p>
        </div>
    </div>
</template>
