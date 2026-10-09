<script setup lang="ts">
import InitLayout from "@/layouts/InitLayout.vue";
import InitApplicationForm from "@/features/init/forms/InitApplicationForm.vue";
import { onMounted } from "vue";
import { useSetupState } from "@/features/init/state/setupState";

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

const { markStepInProgress, onStepSuccess, showForm } = useSetupState();

onMounted(() => markStepInProgress(1));
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
        <InitApplicationForm
            :settings
            :timezoneList
            init
            @success="onStepSuccess"
        />
    </div>
</template>
