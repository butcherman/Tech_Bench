<script setup lang="ts">
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import InitLayout from "@/layouts/InitLayout.vue";
import SubmitButton from "@/core/components/buttons/SubmitButton.vue";
import TechBenchConfigForm from "@/features/administration/forms/TechBenchConfigForm.vue";
import { onMounted, ref } from "vue";
import { router } from "@inertiajs/vue3";
import { welcome, step2 } from "@/wayfinder/routes/init";
import { useSetupState } from "@/features/init/state/setupState";

const { markStepComplete, markStepInProgress } = useSetupState();

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
        <TechBenchConfigForm :settings :timezoneList init @success="onSuccess">
            <template #submit-button>
                <div class="flex justify-center gap-3">
                    <BaseButton
                        :href="welcome.url()"
                        class="basis-1/3"
                        text="Back"
                    />
                    <SubmitButton class="basis-1/3" text="Next" />
                </div>
            </template>
        </TechBenchConfigForm>
    </div>
</template>
