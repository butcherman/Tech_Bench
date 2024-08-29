<template>
    <div>
        <Head title="Notifications" />
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Notification List</div>
                        <UserNotificationList
                            @show-notification="openNotification"
                        />
                    </div>
                </div>
            </div>
            <div class="col-md-8 order-first order-md-last my-4 my-md-0">
                <div class="card">
                    <div class="card-body">
                        <UserNotificationView />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import UserNotificationList from "@/Components/User/UserNotificationList.vue";
import UserNotificationView from "@/Components/User/UserNotificationView.vue";
import { openNotification } from "@/State/NotificationState";
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
