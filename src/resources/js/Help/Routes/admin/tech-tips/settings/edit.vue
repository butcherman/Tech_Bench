<script setup lang="ts">
import TechTipSettingsForm from "@/Help/Forms/TechTip/TechTipSettingsForm.vue";
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
            <h5 class="text-center">Tech Tip Settings</h5>
        </div>
        <TechTipSettingsForm />
    </div>
</template>
