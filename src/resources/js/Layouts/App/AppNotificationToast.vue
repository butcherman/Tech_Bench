<script setup lang="ts">
import Card from "@/Components/_Base/Card.vue";
import { useBroadcastStore } from "@/Stores/BroadcastStore";
import { handleLinkClick } from "@/Composables/links.module";
import { gsap } from "gsap/gsap-core";

const msg = useBroadcastStore();

/**
 * Animations
 */
const onEnter = (el: Element) => {
    gsap.from(el, {
        x: 1000,
        ease: "back.out",
        duration: 0.5,
    });
};

const onLeave = (el: Element, done: () => void) => {
    gsap.to(el, {
        x: 1000,
        ease: "back.in",
        onComplete: done,
    });
};
</script>

<template>
    <Teleport to="body">
        <div id="app-toast-wrapper" class="absolute bottom-2 right-2 w-full">
            <TransitionGroup @enter="onEnter" @leave="onLeave" :css="false">
                <div
                    v-for="toast in msg.notificationToasts"
                    :key="toast.id"
                    class="ms-auto my-2 w-64 notification-toast"
                    :class="{ pointer: toast.href }"
                    @click="handleLinkClick($event, toast.href)"
                >
                    <Card :title="toast.title">
                        <template #append-title>
                            <button @click.stop="msg.removeToastMsg(toast.id)">
                                <fa-icon icon="close" />
                            </button>
                        </template>
                        {{ toast.message }}
                    </Card>
                </div>
            </TransitionGroup>
        </div>
    </Teleport>
</template>

<style scoped>
#app-toast-wrapper {
    overflow: hidden;
    pointer-events: none;
    z-index: 10000;
}

.notification-toast {
    pointer-events: auto;
}
</style>
