import { ref } from "vue";
import { v4 as uuidv4 } from "uuid";

/**
 * Navbar States (for mobile)
 */
export const navbarActive = ref<boolean>(false);
export const toggleNavbar = () => {
    navbarActive.value = !navbarActive.value;
};
export const closeNavbar = () => {
    navbarActive.value = false;
};

/**
 * Flash Alerts
 */
interface flashAlert {
    id: string;
    type: string;
    message: string;
}

export const flashAlerts = ref<flashAlert[]>([]);
//  Push a new alert to DOM
export const pushAlert = (type: string, message: string) => {
    const id = uuidv4();
    flashAlerts.value.push({
        id,
        type,
        message,
    });

    setAutoTimeout(id);
};
//  Manually remove an alert
export const removeAlert = (id: string) => {
    flashAlerts.value = flashAlerts.value.filter((alert) => alert.id !== id);
};
//  Alerts will be auto removed after 30 seconds
const setAutoTimeout = (id: string) => {
    setTimeout(() => {
        removeAlert(id);
    }, 15000);
};
