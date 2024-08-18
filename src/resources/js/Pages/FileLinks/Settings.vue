<template>
    <div>
        <Head title="File Link Settings" />
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">File Link Settings</div>
                        <FileLinkSettingsForm :settings="settings" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import FileLinkSettingsForm from "@/Forms/FileLink/FileLinkSettingsForm.vue";
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{
    settings: {
        default_link_life: number;
        auto_delete: boolean;
        auto_delete_days: number;
        auto_delete_override: boolean;
    };
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
