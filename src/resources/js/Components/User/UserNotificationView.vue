<template>
    <div>
        <div v-if="activeNotification">
            <h3 class="text-center">
                <button
                    type="button"
                    class="btn-close float-end text-small"
                    title="Close Notification"
                    v-tooltip
                    @click="closeNotification"
                />
                {{ activeNotification.data.subject }}
            </h3>
            <hr />
            <component
                id="notification-component"
                :is="asyncComponent"
                v-bind="activeNotification.data.data"
            />
            <hr />
            <div class="text-center">
                <DeleteButton pill small @click="deleteNotification" />
                <button
                    class="btn btn-info btn-sm rounded-5"
                    @click="closeNotification"
                >
                    X Close
                </button>
            </div>
        </div>
        <div v-else>
            <p class="text-center">Click on a Message to View it</p>
        </div>
    </div>
</template>

<script setup lang="ts">
import DeleteButton from "../_Base/Buttons/DeleteButton.vue";
import AtomLoader from "../_Base/Loaders/AtomLoader.vue";
import NotificationLoadFailed from "../Notifications/NotificationLoadFailed.vue";
import { computed, defineAsyncComponent } from "vue";
import {
    activeNotification,
    closeNotification,
    deleteSingleNotification,
} from "@/State/NotificationState";

/**
 * Close and delete the active notification
 */
const deleteNotification = () => {
    if (activeNotification.value) {
        deleteSingleNotification(activeNotification.value?.id);
        closeNotification();
    }
};

/**
 * Load the notification component
 */
const asyncComponent = computed(() => {
    if (!activeNotification.value) {
        return null;
    }

    let componentName = activeNotification.value.type.split(/\W+/).pop();

    return defineAsyncComponent({
        loader: () => import(`../Notifications/${componentName}.vue`),
        loadingComponent: AtomLoader,
        delay: 200,
        errorComponent: NotificationLoadFailed,
        timeout: 3000,
    });
});
</script>
