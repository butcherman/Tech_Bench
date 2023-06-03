<template>
    <Teleport to="body">
        <div class="toast-container position-fixed bottom-0 end-0 p-3">
            <TransitionGroup @enter="onEnter" @leave="onLeave">
                <div
                    v-for="notification in newNotifications"
                    :key="notification.id"
                    class="toast align-items-center fade show"
                >
                    <div class="toast-header">
                        <strong class="me-auto">New Notification</strong>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="toast"
                        ></button>
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
import { newNotifications } from "@/State/NotificationState";
import { gsap } from "gsap";

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
