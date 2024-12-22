<template>
    <Teleport to="body">
        <div id="toast-wrapper" class="fixed bottom-2 right-2 opacity-90">
            <TransitionGroup @enter="onEnter" @leave="onLeave" :css="false">
                <template
                    v-for="toast in notifications.notificationToasts"
                    :key="toast.id"
                >
                    <v-sheet
                        elevation="5"
                        width="250"
                        height="100"
                        class="my-2 contain-strict"
                        rounded="lg"
                    >
                        <font-awesome-icon
                            icon="xmark"
                            class="float-end m-3 text-muted pointer"
                            @click="notifications.removeToastMsg(toast.id)"
                        />
                        <font-awesome-icon
                            icon="inbox"
                            class="float-start m-3 text-muted"
                        />
                        <h5 class="font-bold h-10 py-2">{{ toast.title }}</h5>
                        <div class="text-center py-2">
                            <Link v-if="toast.href" :href="toast.href">
                                {{ toast.message }}
                            </Link>
                            <span>{{ toast.message }}</span>
                        </div>
                    </v-sheet>
                </template>
            </TransitionGroup>
        </div>
    </Teleport>
</template>

<script setup lang="ts">
import { useBroadcastStore } from "@/Stores/BroadcastStore";
import { gsap } from "gsap";

const notifications = useBroadcastStore();

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

<style scoped>
#toast-wrapper {
    z-index: 10000;
}
</style>
