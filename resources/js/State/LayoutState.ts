import { ref } from "vue";
import { v4 as uuidv4 } from "uuid";
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

/***************************************************
 * Enable Broadcasting Channels
 ***************************************************/
export const pusher = Pusher;
export const echo = new Echo({
    broadcaster: 'pusher',
    key: 'app-key',
    wsHost: '192.168.1.250',
    wsPort: 6001,
    wssPort: 6001,
    cluster: 'TechBenchCluster',
    forceTLS: false,
    encrypted: true,
    enableLogging: true,
    enabledTransports: ['ws', 'wss'],

});

/***************************************************
 * Navbar States (for mobile)
 ***************************************************/
export const navbarActive = ref<boolean>(false);
export const toggleNavbar = () => {
    navbarActive.value = !navbarActive.value;
};
export const closeNavbar = () => {
    navbarActive.value = false;
};

/***************************************************
 * Flash Alerts
 ***************************************************/
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
//  Alerts will be auto removed after 15 seconds
const setAutoTimeout = (id: string) => {
    setTimeout(() => {
        removeAlert(id);
    }, 15000);
};
