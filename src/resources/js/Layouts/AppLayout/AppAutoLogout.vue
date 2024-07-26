<template>
    <Modal ref="autoLogoutModal" title="Are You Still There?">
        <p class="text-center">
            You have been idle for more than {{ app.idleTimeout - 1 }} minutes.
        </p>
        <p class="text-center">
            You will be automatically logged out in 60 seconds.
        </p>
        <p class="text-center">
            <button class="btn btn-info" @click="autoLogoutModal?.hide">
                Continue Session
            </button>
        </p>
    </Modal>
</template>

<script setup lang="ts">
import Modal from "@/Components/_Base/Modal.vue";
import { ref, onMounted, onUnmounted } from "vue";
import { useAppStore } from "@/Store/AppStore";
import { router } from "@inertiajs/vue3";

const app = useAppStore();
const autoLogoutModal = ref<InstanceType<typeof Modal> | null>(null);
const warningTimer = ref<number | undefined>();
const logoutTimer = ref<number | undefined>();

const events = [
    "click",
    "mousemove",
    "mousedown",
    "scroll",
    "keypress",
    "load",
];

onMounted(() => {
    events.forEach((event) => window.addEventListener(event, resetTimer));
    startTimer();
});

onUnmounted(() =>
    events.forEach((event) => window.removeEventListener(event, resetTimer))
);

const startTimer = () => {
    warningTimer.value = setTimeout(
        showWarning,
        (app.idleTimeout - 1) * 60 * 1000
    );
    logoutTimer.value = setTimeout(logoutUser, app.idleTimeout * 60 * 1000);
};

const showWarning = () => {
    autoLogoutModal.value?.show();
};

const logoutUser = () => {
    // router.post(route("logout"), { reason: "timeout" });
    console.log("logging user out");
};

const resetTimer = () => {
    clearTimeout(warningTimer.value);
    clearTimeout(logoutTimer.value);

    startTimer();
};
</script>
