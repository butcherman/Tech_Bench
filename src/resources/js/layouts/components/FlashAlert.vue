<script setup lang="ts">
import gsap from "gsap";
import { useAlertStyles } from "../composables/alertStyles";
import { useFlashState } from "../../core/state/flashState";

const { getStatusType, getStatusIcon } = useAlertStyles();
const { flashAlerts } = useFlashState();

/*
|-------------------------------------------------------------------------------
| Animations
|-------------------------------------------------------------------------------
*/
const onEnter = (el: Element, done: () => void): void => {
    gsap.from(el, {
        x: -1000,
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
            id="app-flash-wrapper"
            class="fixed top-5 w-full z-50 flex flex-col items-center overflow-hidden pointer-events-none"
        >
            <TransitionGroup :css="false" @enter="onEnter" @leave="onLeave">
                <div
                    v-for="flash in flashAlerts"
                    class="flash-alert flex justify-between w-11/12 md:w-1/2 px-3 py-2 my-2 rounded-xl text-xl pointer-events-auto opacity-90"
                    :class="getStatusType(flash.level)"
                    :key="flash.id"
                >
                    <div class="flex items-center">
                        <fa-icon :icon="getStatusIcon(flash.level)" />
                    </div>
                    <div class="text-center my-1">{{ flash.message }}</div>
                    <div class="flex items-center">
                        <fa-icon :icon="getStatusIcon(flash.level)" />
                    </div>
                </div>
            </TransitionGroup>
        </div>
    </Teleport>
</template>
