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
                Tech Tips are Knowledge Base articles to help make your job
                easier.
            </p>
            <p>
                Tips can range from official documentation, to tips and tricks
                that you and others have run into while working on equipment.
            </p>
            <p>
                Use the Search Bar to search through the Tech Tips and use the
                Search Filters to narrow down the results.
            </p>
        </div>
    </div>
</template>
