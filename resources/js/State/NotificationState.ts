import axios from "axios";
import { ref, reactive } from "vue";
import { router } from "@inertiajs/vue3";
import type { AxiosResponse } from "axios";

const intervalId = ref<number>(0);
const checkCounter = ref<number>(0);

export const newNotifications = ref<notification[]>([]);
export const displayNotification = ref<notification | null>(null);
export const notifications = reactive<notificationProps>({
    list: [],
    new: 0,
});

/**
 * Display a Modal showing the selected notification
 */
export const showNotification = (notification: notification) => {
    displayNotification.value = notification;
};

/**
 * Remove the active notification
 */
export const closeNotification = () => {
    sendNotificationUpdate("mark", [displayNotification.value?.id!!]);
    displayNotification.value = null;
};

/**
 * Set timer to perform Async check for new notifications every 60 seconds
 */
export const triggerFetchInterval = () => {
    intervalId.value = setInterval(() => fetchNotifications(), 60000);
};

/**
 * End the Async check for new notifications
 */
export const clearFetchInterval = () => {
    clearInterval(intervalId.value);
};

/**
 * Reset the fetch counter
 */
export const resetCheckCounter = () => {
    checkCounter.value = 0;
};

/**
 * Async fetch notifications
 */
export const fetchNotifications = () => {
    //  If the session has been idle for more than 15 minutes, log user out
    if (checkCounter.value > 15) {
        // triggerAutoLogout();
    } else {
        axios
            .post(route("user.notifications"), { action: "fetch" })
            .then((res: AxiosResponse<notificationProps>) => {
                //  Check for new messages to throw an alert
                res.data.list.forEach((msg) => {
                    const existing = notifications.list.filter(
                        (n) => n.id === msg.id
                    )[0];
                    if (!existing) {
                        newNotifications.value.push(msg);
                        notifications.list.unshift(msg);
                        setAutoTimeout(msg.id);
                    }
                });
                //  repopulate the list to update read and deleted messages
                setNotifications(res.data);
                checkCounter.value++;
            });
    }
};

/**
 * Set the current notification state
 */
export const setNotifications = (newList: notificationProps) => {
    notifications.new = newList.new;
    notifications.list = newList.list;
};

/**
 * Remove or Mark notifications a read
 */
export const sendNotificationUpdate = (
    action: "mark" | "delete" | "fetch",
    updateIdList: string[]
) => {
    clearFetchInterval();
    axios
        .post(route("user.notifications"), { action, list: updateIdList })
        .then(() => {
            fetchNotifications();
            triggerFetchInterval();
        });
};

/**
 * remove a specific notification from the New notification List
 */
const removeNotification = (id: string) => {
    newNotifications.value = newNotifications.value.filter((n) => n.id !== id);
};

/**
 * Notification toast will be auto removed after 10 seconds
 */
const setAutoTimeout = (id: string) => {
    setTimeout(() => {
        removeNotification(id);
    }, 10000);
};

/**
 * Popup automatic logout notice.  User has 60 seconds to respond before being logged out automatically
 */
const triggerAutoLogout = () => {
    clearFetchInterval();
    router.post(route("logout"), { reason: "timeout" });
};
