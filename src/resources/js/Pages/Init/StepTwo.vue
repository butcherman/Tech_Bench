<template>
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Basic Settings</div>
                    <p class="text-center">
                        Now we need some basic information about the site.
                        Please enter the Full URL, the Timezone and the maximum
                        filesize upload that will be allowed.
                    </p>
                    <BasicConfigForm
                        :tz-list="timezoneList"
                        :url="settings.url"
                        :timezone="settings.timezone"
                        :max-filesize="settings.max_filesize"
                        :company_name="settings.company_name"
                        init
                        @success="router.get($route('init.step-3'))"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import InitLayout from "@/Layouts/InitLayout.vue";
import BasicConfigForm from "@/Forms/Admin/Config/BasicConfigForm.vue";
import { router } from "@inertiajs/vue3";

defineProps<{
    settings: {
        url: string;
        timezone: string;
        max_filesize: number;
        company_name: string;
    };
    timezoneList: TimezoneList;
}>();
</script>

<script lang="ts">
export default { layout: InitLayout };
</script>
