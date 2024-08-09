import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import { gsap } from "gsap/gsap-core";

export const loading = ref<boolean>(false);
export const markedNotifications = ref<string[]>([]);
export const activeNotification = ref<userNotification | undefined>();
export const allChecked = ref<boolean>(false);

/**
 * Assign the notification data that the view page will use to build view
 */
export const setActiveNotification = (notification: userNotification) => {
    activeNotification.value = notification;
};

/**
 * Mark a notification as read or delete it from the server
 */
export const handleNotifications = (action: "read" | "delete") => {
    loading.value = true;
    router.post(
        route("handle-notifications"),
        {
            action,
            idList: markedNotifications.value,
        },
        {
            only: ["user_notifications"],
            preserveScroll: true,
            onFinish: () => {
                loading.value = false;
                markedNotifications.value = [];
                allChecked.value = false;
            },
        }
    );
};

export const markSingleNotification = (id: string) => {
    router.post(route("handle-notifications"), {
        action: "read",
        idList: [id],
    });
};

export const deleteSingleNotification = (id: string) => {
    router.post(route("handle-notifications"), {
        action: "delete",
        idList: [id],
    });
};

/**
 * Notification Animations
 */
export const showNotification = () => {
    console.log("show notification");

    let timeline = gsap.timeline();
    timeline.to("#notification-list", {
        width: 0,
        duration: 0.5,
    });
    timeline.to(
        "#notification-data",
        {
            width: "100%",
            duration: 0.5,
            delay: 0.01,
        },
        "<"
    );
};

export const hideNotification = () => {
    console.log("hide notification");
    activeNotification.value = undefined;

    let timeline = gsap.timeline();
    timeline.to("#notification-data", {
        width: 0,
        duration: 0.5,
    });
    timeline.to(
        "#notification-list",
        {
            width: "100%",
            duration: 0.5,
            delay: 0.01,
        },
        "<"
    );
};
