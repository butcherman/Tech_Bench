<template>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">User Settings</div>
                    <UserSettingsForm :twoFa="twoFa" :oath="oath" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import UserSettingsForm from "@/Forms/Admin/User/UserSettingsForm.vue";
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{
    twoFa: twoFaConfig;
    oath: oathConfig;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
