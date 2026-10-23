<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
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
                All Customer Sites are listed here. You can select a site to
                view and edit its information, or select the
                <AddButton size="small" pill text="Add Site" /> button to create
                a new site.
            </p>
        </div>
    </div>
</template>
