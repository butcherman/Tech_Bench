<template>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Tech Tip Settings</div>
                    <TipSettingsForm :settings="settings" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import TipSettingsForm from "@/Forms/TechTips/TipSettingsForm.vue";
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{
    settings: {
        allow_comments: boolean;
        allow_public: boolean;
    };
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
