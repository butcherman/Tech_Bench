import { ref } from "vue";
import { echo } from "@/State/LayoutState";
import axios from "axios";

export const newNotificationCount = ref<number>(0);
export const newNotificationReceived = ref<number>(0);
export const notificationList = ref<notification[]>([]);
export const displayNotification = ref<notification | null>(null);
export const loadingState = ref<boolean>(false);

/***************************************************
 * Register to Notification Channel
 ***************************************************/
export const registerNotificationChannel = (username: string) => {
    echo.private(`user-notification.${username}`).notification(
        (data: notificationBroadcast) => {
            let newNotification = {
                created_at: new Date().toDateString().slice(4),
                read_at: null,
                id: data.id,
                notifiableId: 1,
                data: {
                    subject: data.subject,
                    component: data.component,
                    props: data.props,
                },
            };

            notificationList.value.unshift(newNotification);
            newNotificationCount.value++;
            newNotificationReceived.value++;
        }
    );
};

/***************************************************
 * Remove or Mark notifications a read
 ***************************************************/
export const sendNotificationUpdate = (
    action: "mark" | "delete" | "fetch",
    updateIdList: string[]
) => {
    loadingState.value = true;
    axios
        .post(route("user.notifications"), { action, list: updateIdList })
        .then((res) => {
            newNotificationCount.value = res.data.new;
            notificationList.value = res.data.list;
            loadingState.value = false;
        });
};
