<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                Notifications:
                <div v-if="markedNotifications.length" class="float-end">
                    <CheckmarkBadge
                        tooltip="Mark Messages as Read"
                        @click="handleNotifications('read')"
                    />
                    <DeleteBadge
                        tooltip="Delete Messages"
                        @click="handleNotifications('delete')"
                    />
                </div>
            </div>
            <Overlay :loading="loading">
                <div id="notification-wrapper">
                    <div id="notification-list">
                        <UserNotificationList
                            @show-notification="openNotification"
                        />
                    </div>
                    <div id="notification-data">
                        <UserNotificationView />
                    </div>
                </div>
            </Overlay>
        </div>
    </div>
</template>

<script setup lang="ts">
import Overlay from "../_Base/Loaders/Overlay.vue";
import UserNotificationList from "./UserNotificationList.vue";
import UserNotificationView from "./UserNotificationView.vue";
import CheckmarkBadge from "@/Components/_Base/Badges/CheckmarkBadge.vue";
import DeleteBadge from "../_Base/Badges/DeleteBadge.vue";
import {
    loading,
    setActiveNotification,
    markedNotifications,
    handleNotifications,
    showNotification,
    markSingleNotification,
} from "@/State/NotificationState";

const openNotification = (notification: userNotification) => {
    setActiveNotification(notification);
    showNotification();
    markSingleNotification(notification.id);
};
</script>

<style scoped lang="scss">
#notification-wrapper {
    height: 35vh;
    position: relative;
    #notification-list {
        display: block;
        float: left;
        width: 100%;
        overflow-y: auto;
        height: 98%;
        max-height: 100%;
    }
    #notification-data {
        display: block;
        float: left;
        width: 0;
        overflow-y: auto;
        height: 98%;
        max-height: 100%;
    }
}
</style>
