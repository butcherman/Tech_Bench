import { ref } from "vue";
import { router } from "@inertiajs/vue3";

export const loading = ref<boolean>(false);
export const markedNotifications = ref<string[]>([]);
export const activeNotification = ref<userNotification | undefined>();
export const allChecked = ref<boolean>(false);

/**
 * Show a selected notification
 */
export const openNotification = (notification: userNotification) => {
    activeNotification.value = notification;
    markSingleNotification(notification.id);
};

/**
 * Close the active notification
 */
export const closeNotification = () => {
    activeNotification.value = undefined;
};

/**
 * Mark a notification as read or delete it from the server
 */
export const handleNotifications = (action: "read" | "delete") => {
    loading.value = true;
    router.post(
        route("handle-notifications"),
        {
            action,
            idList: markedNotifications.value,
        },
        {
            only: ["user_notifications"],
            preserveScroll: true,
            onFinish: () => {
                loading.value = false;
                markedNotifications.value = [];
                allChecked.value = false;
            },
        }
    );
};

/**
 * Mark a single notification as read
 */
export const markSingleNotification = (id: string) => {
    router.post(route("handle-notifications"), {
        action: "read",
        idList: [id],
    });
};

/**
 * Delete a single notification
 */
export const deleteSingleNotification = (id: string) => {
    router.post(route("handle-notifications"), {
        action: "delete",
        idList: [id],
    });
};
