<template>
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Tech Bench Settings</div>
                    <BasicConfigForm
                        :tz-list="timezoneList"
                        :url="settings.url"
                        :timezone="settings.timezone"
                        :max-filesize="settings.max_filesize"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import BasicConfigForm from "@/Forms/Admin/Config/BasicConfigForm.vue";
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{
    settings: {
        url: string;
        timezone: string;
        max_filesize: number;
    };
    timezoneList: TimezoneList;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
