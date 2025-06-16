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
            All notes will be listed here. Click on a note for more details, or
            click <AddButton text="Add Note" pill size="small" /> to create a
            new Note.
        </div>
    </div>
</template>
