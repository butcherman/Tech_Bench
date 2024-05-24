<template>
    <div>
        <Head title="Log Settings" />
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Log Settings</div>
                        <LogSettingsForm
                            :days="days"
                            :log-level="logLevel"
                            :level-list="levelList"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import LogSettingsForm from "@/Forms/Maintenance/LogSettingsForm.vue";
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{
    days: number;
    logLevel: string;
    levelList: logLevel[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
