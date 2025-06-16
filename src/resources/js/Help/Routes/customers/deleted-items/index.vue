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
                Equipment, Contacts, Notes, and Files that have been deleted
                will go to this page and can be recovered for up to 90 days (if
                auto purge is enabled).
            </p>
            <p>
                From this page an administrator can restore or remove a deleted
                item.
            </p>
        </div>
    </div>
</template>
