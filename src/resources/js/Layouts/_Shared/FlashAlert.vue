<script setup lang="ts">
import { gsap } from "gsap";
import { useAppStore } from "@/Stores/AppStore";
import { getStatusType, getStatusIcon } from "@/Composables/styleData.module";

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
                        class="app-flash-message py-3 px-2 my-3 rounded-xl flex text-xl opacity-90"
                        :class="getStatusType(flash.type)"
                    >
                        <div class="mx-2">
                            <fa-icon :icon="getStatusIcon(flash.type)" />
                        </div>
                        <div class="grow text-center">
                            {{ flash.message }}
                        </div>
                        <div class="mx-2">
                            <fa-icon
                                icon="xmark"
                                class="pointer"
                                @click="app.removeFlashMsg(flash.id)"
                            />
                        </div>
                    </div>
                </TransitionGroup>
            </div>
        </div>
    </Teleport>
</template>
