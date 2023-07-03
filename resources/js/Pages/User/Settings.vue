<template>
    <Head title="User Settings" />
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Account</div>
                    <UserAccountForm :user="user" />
                </div>
            </div>
        </div>
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body d-flex flex-column">
                    <div class="card-title">Notifications</div>
                    <UserNotificationForm
                        :notifications="notifications"
                        :two-fa="twoFa"
                        :username="user.username"
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
    user: user;
    notifications: userSettings[];
    twoFa: {
        allow_sms: boolean;
        sms_notifications: boolean;
        phone: string;
    }
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
