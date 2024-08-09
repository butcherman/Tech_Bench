<template>
    <div class="h-100 d-flex flex-column">
        <h3 class="text-center">
            <button
                type="button"
                class="btn-close float-end text-small"
                title="Close Notification"
                v-tooltip
                @click="hideNotification"
            />
            {{ activeNotification?.data.subject }}
        </h3>
        <template v-if="activeNotification">
            <component
                id="notification-component"
                :is="asyncComponent"
                v-bind="activeNotification.data.data"
            />
        </template>
        <div class="mt-auto">
            <DeleteButton pill small @click="deleteNotification" />
            <button
                class="btn btn-info btn-sm rounded-5"
                @click="hideNotification"
            >
                X Close
            </button>
        </div>
    </div>
</template>

<script setup lang="ts">
import AtomLoader from "../_Base/Loaders/AtomLoader.vue";
import NotificationLoadFailed from "@/Components/Notifications/NotificationLoadFailed.vue";
import DeleteButton from "../_Base/Buttons/DeleteButton.vue";
import { computed, defineAsyncComponent } from "vue";
import {
    activeNotification,
    hideNotification,
    deleteSingleNotification,
} from "@/State/NotificationState";

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

const deleteNotification = () => {
    if (activeNotification.value) {
        deleteSingleNotification(activeNotification.value?.id);
        hideNotification();
    }
};
</script>

<style scoped lang="scss">
#notification-component {
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}
</style>
