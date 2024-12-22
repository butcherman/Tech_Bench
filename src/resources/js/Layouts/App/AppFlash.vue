<template>
    <Teleport to="body">
        <div id="flash-wrapper" class="fixed top-2 w-full">
            <TransitionGroup @enter="onEnter" @leave="onLeave" :css="false">
                <div
                    v-for="flash in app.flashAlerts"
                    :key="flash.id"
                    class="md:w-3/4 mx-auto my-2 flash-alert"
                >
                    <v-alert
                        border="start"
                        class="text-center w-100 opacity-90"
                        elevation="4"
                        :color="getFlashColor(flash.type)"
                        :icon="getFlashIcon(flash.type)"
                        :text="flash.message"
                        closable
                    />
                </div>
            </TransitionGroup>
        </div>
    </Teleport>
</template>

<script setup lang="ts">
import { gsap } from "gsap";
import { useAppStore } from "@/Stores/AppStore";
import { getFlashColor, getFlashIcon } from "@/Composables/Flash";

const app = useAppStore();

/*
|---------------------------------------------------------------------------
| Enter and Leave Animations
|---------------------------------------------------------------------------
*/
const onEnter = (el: Element, done: () => void) => {
    gsap.from(el, {
        x: -2000,
        ease: "back.out",
        duration: 0.5,
        onComplete: done,
    });
};

const onLeave = (el: Element, done: () => void) => {
    gsap.to(el, {
        x: 2000,
        ease: "back.in",
        onComplete: done,
    });
};
</script>

<style scoped>
#flash-wrapper {
    z-index: 10000;
}
</style>
