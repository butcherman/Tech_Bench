<template>
    <Modal ref="notificationModal" :title="displayNotification?.data.subject">
        <component
            :is="asyncComponent"
            v-bind="displayNotification?.data.props"
            @hideNotification="notificationModal?.hide()"
        />
        <template #footer>
            <DeleteButton @click="deleteNotification" />
        </template>
    </Modal>
</template>

<script setup lang="ts">
import Modal from "@/Components/_Base/Modal.vue";
import NotificationLoadFailed from "./NotificationLoadFailed.vue";
import AtomLoader from "@/Components/_Base/Loaders/AtomLoader.vue";
import DeleteButton from "@/Components/_Base/Buttons/DeleteButton.vue";
import { ref, watch, computed, defineAsyncComponent } from "vue";
import {
    sendNotificationUpdate,
    displayNotification,
} from "@/State/NotificationState";

const notificationModal = ref<InstanceType<typeof Modal> | null>(null);

/**
 * Async get the component for the notification from the server
 */
const asyncComponent = computed(() => {
    if (displayNotification.value) {
        return defineAsyncComponent({
            loader: () =>
                import(
                    /* @vite-ignore */ `./${displayNotification.value?.data.component}.vue`
                ),
            loadingComponent: AtomLoader,
            delay: 200,
            errorComponent: NotificationLoadFailed,
            timeout: 3000,
        });
    }

    return null;
});

watch(displayNotification, (newDisplayNotification) => {
    if (newDisplayNotification) {
        notificationModal.value?.show();

        sendNotificationUpdate("mark", [newDisplayNotification.id]);
    }
});

const deleteNotification = () => {
    if (displayNotification.value) {
        const notificationId = displayNotification.value.id;

        notificationModal.value?.hide();
        sendNotificationUpdate("delete", [notificationId]);
    }
};
</script>
