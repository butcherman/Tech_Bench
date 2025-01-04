<template>
    <Teleport to="body">
        <div
            id="app-flash-wrapper"
            class="absolute top-10 w-full justify-items-center"
        >
            <div class="w-11/12 md:w-1/2">
                <TransitionGroup @enter="onEnter" @leave="onLeave" :css="false">
                    <div
                        v-for="flash in app.flashAlerts"
                        :key="flash.id"
                        class="app-flash-message"
                    >
                        <Message
                            class="my-2"
                            :severity="getStatusType(flash.type)"
                            pt:text:class="grow text-center"
                            closable
                        >
                            <template #icon>
                                <fa-icon :icon="getStatusIcon(flash.type)" />
                            </template>
                            {{ flash.message }}
                        </Message>
                    </div>
                </TransitionGroup>
            </div>
        </div>
    </Teleport>
</template>

<script setup lang="ts">
import { Message } from "primevue";
import { gsap } from "gsap";
import { useAppStore } from "@/Stores/AppStore";
import { getStatusType, getStatusIcon } from "@/Composables/statusData.module";

const app = useAppStore();

/*
|-------------------------------------------------------------------------------
| Enter and Leave Animations
|-------------------------------------------------------------------------------
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
        x: 1000,
        ease: "back.in",
        onComplete: done,
    });
};
</script>

<style scoped>
#app-flash-wrapper {
    overflow: hidden;
    pointer-events: none;
    z-index: 10000;
}

.app-flash-message {
    pointer-events: auto;
}
</style>
