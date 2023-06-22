<template>
    <div>
        <Head title="Application Configuration" />
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Application Configuration</div>
                        <SettingsForm :settings="settings" :tz-list="tzList" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import SettingsForm from '@/Forms/Admin/Config/SettingsForm.vue';
import { ref, reactive, onMounted, AppConfig } from "vue";

const props = defineProps<{
    settings: appConfig;
    tzList: tzList;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout }
</script>
