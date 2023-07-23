<template>
    <Teleport to="body">
        <div class="toast-container position-fixed bottom-0 end-0 p-3">
            <TransitionGroup @enter="onEnter" @leave="onLeave">
                <div
                    v-for="notification in newNotifications"
                    :key="notification.id"
                    class="toast align-items-center fade show pointer"
                    @click="showNotification(notification)"
                >
                    <div class="toast-header">
                        <strong class="me-auto">New Notification</strong>
                        <button
                            type="button"
                            class="btn-close"
                            @click.stop="removeNotification(notification.id)"
                        />
                    </div>
                    <div class="toast-body text-center">
                        {{ notification.data.subject }}
                    </div>
                </div>
            </TransitionGroup>
        </div>
    </Teleport>
</template>

<script setup lang="ts">
import { gsap } from "gsap";
import { ref, watch } from 'vue';
import { newNotificationReceived, notificationList, displayNotification } from '@/State/NotificationState';

const newNotifications = ref<notification[]>([]);

/**
 * Watch the notification count, on new message trigger alert toast
 */
watch(newNotificationReceived, () => {
    let msg = notificationList.value[0];

    newNotifications.value.push(msg);
    setAutoTimeout(msg.id);
});

const removeNotification = (id: string) => {
    newNotifications.value = newNotifications.value.filter((n) => n.id !== id);
}

const showNotification = (notification: notification) => {
    displayNotification.value = notification;
}

/**
 * Notifications will automatically be removed after 10 seconds
 */
const setAutoTimeout = (id: string) => {
    setTimeout(() => {
        removeNotification(id);
    }, 10000);
}

/**
 * Animations
 */
const onEnter = (el: Element) => {
    gsap.from(el, {
        x: 1000,
    });
};

const onLeave = (el: Element) => {
    gsap.to(el, {
        x: 1000,
    });
};
</script>
