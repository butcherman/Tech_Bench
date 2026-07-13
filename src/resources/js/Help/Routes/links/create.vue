<script setup lang="ts">
import FileLinkCreateForm from "@/Help/Forms/FileLink/FileLinkCreateForm.vue";
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

defineProps<{
    allowCustomUrl: boolean;
}>();

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
            <p class="text-center">Use this form to create a new file link.</p>
        </div>
        <FileLinkCreateForm :allow-custom-url="allowCustomUrl" />
    </div>
</template>
