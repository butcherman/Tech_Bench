import { ref } from "vue";
import { echo } from '@/State/LayoutState';
import axios from "axios";

export const newNotificationCount = ref<number>(0);
export const notificationList = ref<notification[]>([]);

export const loadingState = ref<boolean>(false);





/***************************************************
 * Register to Notification Channel
 ***************************************************/
echo.private('App.Models.User.1').notification((notification) => {
    console.log(notification);

    newNotificationCount.value++;
});


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
