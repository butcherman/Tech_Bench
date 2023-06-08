<template>
    <Modal
        ref="notificationModal"
        :title="displayNotification?.data.subject"
        @hidden="closeNotification"
    >
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
import Modal from "@/Components/Base/Modal/Modal.vue";
import NotificationLoadFailed from "./NotificationLoadFailed.vue";
import AtomLoader from "../Base/Loader/AtomLoader.vue";
import DeleteButton from "../Base/Buttons/DeleteButton.vue";
import { ref, watch, computed, defineAsyncComponent } from "vue";
import {
    closeNotification,
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
                import(`./${displayNotification.value?.data.component}.vue`),
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
        console.log(newDisplayNotification);
    }
});

const deleteNotification = () => {
    if(displayNotification.value)
    {
        const notificationId = displayNotification.value.id;

        notificationModal.value?.hide();
        sendNotificationUpdate('delete', [notificationId]);
    }
}
</script>
