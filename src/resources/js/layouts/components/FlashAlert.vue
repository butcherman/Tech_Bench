<script setup lang="ts">
import BannerAlert from "@/core/components/alerts/BannerAlert.vue";
import gsap from "gsap";
import { onMounted, onUnmounted, ref } from "vue";
import { router } from "@inertiajs/vue3";
import { useAlertState } from "@/core/state/alertState";

const { flashAlerts, pushFlashAlert } = useAlertState();

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

/*
|-------------------------------------------------------------------------------
| Regester the event listener to monitor for flash messages
|-------------------------------------------------------------------------------
*/
const flashListener = ref();
onMounted(() => {
    flashListener.value = router.on("flash", (event) => {
        let msg = event.detail.flash;
        if (msg.banner) {
            pushFlashAlert(msg.banner);
        }
    });
});

onUnmounted(() => flashListener.value());
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
                    :key="flash.id"
                >
                    <BannerAlert
                        class="w-full"
                        :text="flash.message"
                        :variant="flash.variant"
                    />
                </div>
            </TransitionGroup>
        </div>
    </Teleport>
</template>
