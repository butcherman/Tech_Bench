<template>
    <div>
        <div class="text-center">
            <button class="btn btn-info" @click="toggleCollapse">
                Toggle Collapse
            </button>
        </div>
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
    </div>
</template>

<script setup lang="ts">
import Overlay from "../_Base/Loaders/Overlay.vue";
import UserNotificationList from "./UserNotificationList.vue";
import UserNotificationView from "./UserNotificationView.vue";
import CheckmarkBadge from "@/Components/_Base/Badges/CheckmarkBadge.vue";
import DeleteBadge from "../_Base/Badges/DeleteBadge.vue";
import { ref, reactive, onMounted } from "vue";
import { gsap } from "gsap";
import { useAppStore } from "@/Store/AppStore";
import {
    loading,
    setActiveNotification,
    markedNotifications,
    handleNotifications,
    showNotification,
    hideNotification,
} from "@/State/NotificationState";

const showNotificationList = ref(true);

const openNotification = (notification: userNotification) => {
    console.log("open", notification);
    setActiveNotification(notification);
};

/*******************************************************************************
 * Toggle Between Notification List and Notification Data
 *******************************************************************************/
const toggleCollapse = () => {
    if (showNotificationList.value) {
        // console.log("shrink list");
        // hideList();
        showNotification();
    } else {
        // console.log("grow list");
        // showList();
        hideNotification();
    }

    showNotificationList.value = !showNotificationList.value;
};
</script>

<style scoped lang="scss">
#notification-wrapper {
    height: 45vh;
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
