import { defineStore } from 'pinia';

/**
 * Notification Store holds all of the messages/notifications for
 * the logged in user
 */
export const useNotificationStore = defineStore('notification', {
    state: () => {
        return {
            notificationList: [],
            newCount: 0,
        }
    },
    actions: {
        importNotifications(notifObj)
        {
            this.notificationList = [];
            this.newCount         = notifObj.new;

            notifObj.list.forEach(item => {
                item.checked = true;
                item.loading = false;
                this.notificationList.push(item);
            });
        },
    }
});
