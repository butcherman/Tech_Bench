<template>
    <Teleport to="body">
        <div class="toast-container p-3">
            <TransitionGroup
                @enter="onEnter"
                @before-leave="onLeave"
                :css="false"
            >
                <div
                    v-for="toast in app.notificationToasts"
                    :key="toast.id"
                    class="toast align-items-center w-100"
                >
                    <div class="toast-header">
                        <strong class="me-auto">{{ toast.title }}</strong>
                        <button
                            type="button"
                            class="btn-close"
                            @click="app.removeToastMsg(toast.id)"
                        />
                    </div>
                    <div class="toast-body text-center">
                        <div class="row align-items-center m-0 p-0">
                            <div class="col-1 pe-4"></div>
                            <div class="col">{{ toast.message }}</div>
                        </div>
                    </div>
                </div>
            </TransitionGroup>
        </div>
    </Teleport>
</template>

<script setup lang="ts">
import { useAppStore } from "@/Store/AppStore";
import { gsap } from "gsap";

const app = useAppStore();

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

const onLeave = (el: Element) => {
    gsap.to(el, {
        x: 1000,
        ease: "back.in",
    });
};
</script>

<style scoped lang="scss">
@import "../../../scss/Layouts/appLayout.scss";
.toast-container {
    position: fixed;
    bottom: 0;
    right: 0;
    @media (min-width: $brk-lg) {
        width: 25%;
    }
    @media (max-width: $brk-lg) {
        width: 75%;
    }
    .toast {
        line-height: 1.2em;
        font-size: 1.2em;
        display: block;
        opacity: 0.9;
    }
}
</style>
