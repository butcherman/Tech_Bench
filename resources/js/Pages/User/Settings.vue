<template>
    <Head title="User Settings" />
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Account</div>
                    <UserAccountForm :user="app.user" />
                </div>
            </div>
        </div>
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body d-flex flex-column">
                    <div class="card-title">Notifications</div>
                    <UserNotificationForm
                        :settings="settings"
                        :username="app.user?.username"
                        class="d-flex flex-column flex-grow-1"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import UserAccountForm from "@/Forms/User/UserAccountForm.vue";
import UserNotificationForm from "@/Forms/User/UserNotificationForm.vue";

defineProps<{
    app: appProps;
    settings: userSettings[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
