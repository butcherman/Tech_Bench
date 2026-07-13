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
                When a tech tip is created, a "Tech Tip Type" must be selected.
                This helps to determine the purpose of the Tech Tip at a quick
                glance. Common options are:
            </p>
        </div>
        <div class="flex justify-center">
            <ul class="list-disc">
                <li>Tech Tip (generic kb article)</li>
                <li>Documentation (official manufacturer documentation)</li>
                <li>Software (applications for this Tech Tip)</li>
            </ul>
        </div>
    </div>
</template>
