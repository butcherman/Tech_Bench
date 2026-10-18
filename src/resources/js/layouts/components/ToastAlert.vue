<script setup lang="ts">
import BaseBadge from "@/core/components/badges/BaseBadge.vue";
import Card from "@/core/components/Card.vue";
import gsap from "gsap";
import { useAlertState } from "@/core/state/alertState";
import { useLinkHelper } from "@/core/composables/linkHelper";

const { toastAlerts, removeToastMsg } = useAlertState();

/**
 * Handle a toast being closed manually
 */
const onCloseToast = (toast: ToastAlert) => {
    if (!toast.id) return;

    removeToastMsg(toast.id);
};

/**
 * Handle a toast being clicked when it has a link attribute
 */
const onToastClick = (event: MouseEvent, toast: ToastAlert) => {
    if (!toast.href) return;

    useLinkHelper(event, { href: toast.href });
};

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
            class="fixed bottom-2 right-2 w-full overflow-hidden flex flex-col items-end z-50 pointer-events-none"
        >
            <TransitionGroup :css="false" @enter="onEnter" @leave="onLeave">
                <div
                    v-for="toast in toastAlerts"
                    class="my-2 w-64 pointer-events-auto"
                    :class="{ pointer: toast.href }"
                    :key="toast.id"
                    :id="`toast-id-${toast.id}`"
                >
                    <Card
                        :title="toast.title"
                        @click="onToastClick($event, toast)"
                    >
                        <template #append-title>
                            <BaseBadge
                                icon="close"
                                variant="light"
                                class="pointer"
                                @click.stop="onCloseToast(toast)"
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
