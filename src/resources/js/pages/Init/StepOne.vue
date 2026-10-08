<script setup lang="ts">
import InitLayout from "@/layouts/InitLayout.vue";
import TechBenchConfigForm from "@/features/administration/forms/TechBenchConfigForm.vue";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import { step2 } from "@/wayfinder/routes/init";
import { useSetupState } from "@/features/init/state/setupState";

const { markStepComplete } = useSetupState();

defineProps<{
    settings: {
        url: string;
        company_name: string;
        timezone: string;
        max_filesize: number;
        welcome_message: string;
        home_links: { url: string; text: string }[];
    };
    timezoneList: TimezoneList[];
}>();

const showForm = ref(true);

const onSuccess = () => {
    showForm.value = false;

    markStepComplete(1);
    router.get(step2.url());
};
</script>

<script lang="ts">
export default { layout: InitLayout };
</script>
<template>
    <div v-if="showForm" class="flex flex-col justify-center p-2">
        <h1 class="text-center">Application Settings</h1>
        <p class="text-center">
            To start, we need some basic information about the site. Please
            enter the Full URL, the Timezone and the maximum filesize upload
            that will be allowed.
        </p>
        <TechBenchConfigForm
            :settings
            :timezoneList
            init
            @success="onSuccess"
        />
    </div>
</template>
