import axios from "axios";
import { ref, reactive } from "vue";
import type { AxiosResponse } from "axios";

const intervalId = ref(0);

export const newNotifications = ref([]);

export const notifications = reactive<notificationProps>({
    list: [],
    new: 0,
});

/**
 * Async check for new notifications every 60 seconds
 */
export const triggerFetchInterval = () => {
    // intervalId.value = setInterval(() => fetchNotifications(), 6000);
};

/**
 * Async fetch notifications
 */
// export const fetchNotifications = () => {
//     newNotifications.value = [];
//     console.log('fetching notifications');
//     axios.post(route("notifications"), { action: "fetch" }).then((res: AxiosResponse<notificationProps>) => {
//         notifications.new = res.data.new;
//         if (res.data.list.length !== notifications.list.length) {
//             const difference = res.data.list.length - notifications.list.length;

//             console.log('difference is', difference);

//             //  If the list is longer, produce a toast as a notification
//             if (difference > 0) {
//                 console.log("new messages");
//                 for (let i = 0; i < difference; i++) {
//                     console.log("new message", res.data.list[i]);
//                     newNotifications.value.push(res.data.list[i]);
//                 }
//             }

//             notifications.list = res.data.list;
//         }
//     });
// };

export const setNotifications = (newList: notificationProps) => {
    notifications.list = newList.list;
    notifications.new = newList.new;
};

// export const sendNotificationUpdate = (
//     action: "mark" | "delete" | "fetch",
//     updateIdList: string[]
// ) => {
//     clearInterval(intervalId.value);
//     axios
//         .post(route("notifications"), { action, list: updateIdList })
//         .then(() => {
//             fetchNotifications();
//             triggerFetchInterval();
//         });
// };
