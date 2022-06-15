import { defineStore } from 'pinia';

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
