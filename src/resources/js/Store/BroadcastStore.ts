/**
 * Broadcast Store handles Websocket communication
 */

import { ref } from "vue";
import { defineStore } from "pinia";
import { useAppStore } from "./AppStore";
import { v4 as uuidv4 } from "uuid";

export const useBroadcastStore = defineStore("broadcastStore", () => {
    const app = useAppStore();
    const notificationToasts = ref<toastData[]>([]);

    /***************************************************************************
     * Register to Notification Channel to receive live updates
     ***************************************************************************/
    const registerNotificationChannel = () => {
        if (app.user) {
            Echo.private(`App.Models.User.${app.user.user_id}`).notification(
                (data: toastData) => {
                    pushToastMsg(data.message, data.title, data.href);
                }
            );
        }
    };

    /**
     * Register to any private channel
     */
    const registerChannel = (channelName: string) => {
        console.log(channelName);
    };

    /***************************************************************************
     * Notification Toast shows notifications across bottom right corner
     ***************************************************************************/
    const pushToastMsg = (
        message: string,
        title: string = "New Notification",
        href: undefined | string = undefined
    ) => {
        let toastId = uuidv4();

        notificationToasts.value.push({
            id: toastId,
            title,
            message,
            href,
        });
        setToastTimeout(toastId);
    };

    // Manually Remove a Toast Message
    const removeToastMsg = (id: string) => {
        notificationToasts.value = notificationToasts.value.filter(
            (toast) => toast.id !== id
        );
    };

    // Auto Delete Toast after 15 seconds
    const setToastTimeout = (id: string) => {
        setTimeout(() => {
            removeToastMsg(id);
        }, 15000);
    };

    return {
        registerNotificationChannel,
        registerChannel,
        notificationToasts,
        removeToastMsg,
    };
});
