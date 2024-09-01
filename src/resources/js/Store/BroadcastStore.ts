/**
 * Broadcast Store handles Websocket communication
 */

import { defineStore } from "pinia";
import { useAppStore } from "./AppStore";

export const useBroadcastStore = defineStore("broadcastStore", () => {
    const app = useAppStore();

    const registerNotificationChannel = () => {
        if (app.user) {
            console.log(app.user);
            Echo.private(`App.Models.User.${app.user.user_id}`).notification(
                (data) => console.log(data)
            );
        }
    };

    return {
        registerNotificationChannel,
    };
});
