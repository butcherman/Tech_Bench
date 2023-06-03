import axios from "axios";
import { ref, reactive } from "vue";
import type { AxiosResponse } from "axios";

const intervalId = ref(0);

export const newNotifications = ref<notification[]>([]);
export const notifications = reactive<notificationProps>({
    list: [],
    new: 0,
});

/**
 * Async check for new notifications every 60 seconds
 */
export const triggerFetchInterval = () => {
    intervalId.value = setInterval(() => fetchNotifications(), 15000);
};

/**
 * Async fetch notifications
 */
export const fetchNotifications = () => {
    console.log("fetching notifications");
    axios
        .post(route("notifications"), { action: "fetch" })
        .then((res: AxiosResponse<notificationProps>) => {
            notifications.new = res.data.new;
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
            notifications.list = res.data.list;
        });
};

export const setNotifications = (newList: notificationProps) => {
    notifications.new = newList.new;
    notifications.list = newList.list;
};

export const sendNotificationUpdate = (
    action: "mark" | "delete" | "fetch",
    updateIdList: string[]
) => {
    clearInterval(intervalId.value);
    axios
        .post(route("notifications"), { action, list: updateIdList })
        .then(() => {
            console.log("done");
            fetchNotifications();
            triggerFetchInterval();
        });
};

const removeNotification = (id: string) => {
    newNotifications.value = newNotifications.value.filter((n) => n.id !== id);
};

//  Notification toast will be auto removed after 10 seconds
const setAutoTimeout = (id: string) => {
    setTimeout(() => {
        removeNotification(id);
    }, 10000);
};
