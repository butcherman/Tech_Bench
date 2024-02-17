<template>
    <Teleport to="body">
        <div class="toast-container translate-middle-x fade show p-3">
            <TransitionGroup
                @enter="onEnter"
                @before-leave="onLeave"
                :css="false"
            >
                <div
                    v-for="alert in app.flashAlerts"
                    :key="alert.id"
                    class="toast align-items-center w-100"
                    :class="`text-bg-${alert.type}`"
                >
                    <div class="toast-body text-center">
                        <div class="row align-items-center m-0 p-0">
                            <div class="col-1 pe-4">
                                <fa-icon
                                    :icon="getAlertIcon(alert.type)"
                                    class="little-larger-text"
                                />
                            </div>
                            <div class="col">{{ alert.message }}</div>
                            <div class="col-1 ps-4">
                                <button
                                    type="button"
                                    class="btn-close float-end"
                                    @click="app.removeFlashMsg(alert.id)"
                                />
                            </div>
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
import { getAlertIcon } from "@/Modules/AlertStyling.module";

const app = useAppStore();

/**
 * Animations
 */
const onEnter = (el: Element) => {
    gsap.from(el, {
        x: -2000,
        ease: "back.out",
        duration: 0.5,
    });
};

const onLeave = (el: Element) => {
    gsap.to(el, {
        x: 2000,
        ease: "back.in",
    });
};
</script>

<style scoped lang="scss">
@import "../../../scss/Layouts/appLayout.scss";
.toast-container {
    position: fixed;
    top: 10px;
    left: 50%;
    @media (min-width: $brk-lg) {
        width: 50%;
    }
    @media (max-width: $brk-lg) {
        width: 100%;
    }
    .toast {
        border-radius: 10px;
        overflow: hidden;
        line-height: 1.2em;
        font-size: 1.2em;
        display: block;
        opacity: 0.9;
        .badge {
            font-size: 1em;
        }

        .toast-body {
            .little-larger-text {
                font-size: 1.8rem;
            }
        }
    }
}
</style>
