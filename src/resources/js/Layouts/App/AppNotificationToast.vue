<script setup lang="ts">
import BaseBadge from "@/Components/_Base/Badges/BaseBadge.vue";
import Card from "@/Components/_Base/Card.vue";
import gsap from "gsap";
import { handleLinkClick } from "@/Composables/links.module";
import { useBroadcastStore } from "@/Stores/BroadcastStore";

const msg = useBroadcastStore();

/*
|-------------------------------------------------------------------------------
| Animations
|-------------------------------------------------------------------------------
*/
const onEnter = (el: Element, done: () => void): void => {
    gsap.from(el, {
        x: 1000,
        ease: "back.out",
        duration: 0.5,
        onComplete: done,
    });
};

const onLeave = (el: Element, done: () => void): void => {
    gsap.to(el, {
        x: 1000,
        ease: "back.in",
        duration: 0.5,
        onComplete: done,
    });
};
</script>

<template>
    <Teleport to="body">
        <div
            id="app-toast-wrapper"
            class="absolute bottom-2 right-2 w-full overflow-hidden flex flex-col items-end"
        >
            <TransitionGroup :css="false" @enter="onEnter" @leave="onLeave">
                <div
                    v-for="toast in msg.notificationToasts"
                    class="my-2 w-64 notification-toast"
                    :class="{ pointer: toast.href }"
                    :key="toast.id"
                    :id="`toast-id-${toast.id}`"
                    @click="handleLinkClick($event, toast.href)"
                >
                    <Card :title="toast.title">
                        <template #append-title>
                            <BaseBadge
                                icon="close"
                                variant="light"
                                @click.stop="msg.removeToastMsg(toast.id)"
                            />
                        </template>
                        <div>
                            {{ toast.message }}
                        </div>
                    </Card>
                </div>
            </TransitionGroup>
        </div>
    </Teleport>
</template>

<style scoped>
#app-toast-wrapper {
    pointer-events: none;
}

.notification-toast {
    pointer-events: auto;
}
</style>
