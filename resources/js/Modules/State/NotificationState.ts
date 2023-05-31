import axios from "axios";
import { reactive } from "vue";

export const notifications = reactive<notificationProps>({
    list: [],
    new: 0,
});

export const fetchNotifications = () => {
    axios.post(route("notifications"), { action: "fetch" }).then((res) => {
        notifications.list = res.data.list;
        notifications.new = res.data.new;
    });
};

export const setNotifications = (newList: notificationProps) => {
    notifications.list = newList.list;
    notifications.new = newList.new;
};

export const sendNotificationUpdate = (
    action: "mark" | "delete" | "fetch",
    updateIdList: string[]
) => {
    axios
        .post(route("notifications"), { action, list: updateIdList })
        .then(() => {
            fetchNotifications();
        });
};
