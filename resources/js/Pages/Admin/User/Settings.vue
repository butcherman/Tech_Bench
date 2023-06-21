<template>
    <Head title="User Settings" />
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">User Settings</div>
                    <UserSettingsForm
                        :allow_login="allow_login"
                        :allow_register="allow_register"
                        :tenant="tenant"
                        :client_id="client_id"
                        :client_secret="client_secret"
                        :redirect-uri="redirectUri"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import UserSettingsForm from "@/Forms/Admin/User/UserSettingsForm.vue";

defineProps<{
    allow_login: boolean;
    allow_register: boolean;
    tenant: string | null;
    client_id: string | null;
    client_secret: string | null;
    redirectUri: string | null;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
