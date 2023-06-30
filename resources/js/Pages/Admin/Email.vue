<template>
    <div>
        <Head title="Email Settings" />
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Email Settings</div>
                        <EmailConfigForm :settings="settings" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import EmailConfigForm from "@/Forms/Admin/Config/EmailConfigForm.vue";
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{
    settings: emailSettings;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
