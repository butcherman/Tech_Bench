import { readonly, ref } from "vue";
import { v4 } from "uuid";

const flashAlerts = ref<FlashAlert[]>([]);
const toastAlerts = ref<ToastAlert[]>([]);

export const useAlertState = () => {
    /*
    |---------------------------------------------------------------------------
    | Flash Alerts
    |---------------------------------------------------------------------------
    */

    /**
     * Push a new Flash Alert into the flash queue
     */
    const pushFlashAlert = (flashAlert: FlashAlert): void => {
        // Make sure the message has an ID
        if (!Object.hasOwn(flashAlert, "id")) {
            flashAlert.id = v4();
        }

        console.log(flashAlert);

        flashAlerts.value.push(flashAlert);
        if (flashAlert.id) {
            setFlashTimeout(flashAlert.id);
        }
    };

    /**
     * Manually remove message
     */
    const removeFlashMsg = (id: string) => {
        flashAlerts.value = flashAlerts.value.filter(
            (alert) => alert.id !== id,
        );
    };

    /**
     * Auto delete message after 15 seconds
     */
    const setFlashTimeout = (id: string) => {
        setTimeout(() => {
            removeFlashMsg(id);
        }, 5000);
    };

    /*
    |---------------------------------------------------------------------------
    | Toast Alerts
    |---------------------------------------------------------------------------
    */

    /**
     * Push a new Flash Alert into the flash queue
     */
    const pushToastAlert = (toastAlert: ToastAlert): void => {
        // Make sure the message has an ID
        if (!Object.hasOwn(toastAlert, "id")) {
            toastAlert.id = v4();
        }

        toastAlerts.value.push(toastAlert);

        if (toastAlert.id) {
            setToastTimeout(toastAlert.id);
        }
    };

    /**
     * Manually remove message
     */
    const removeToastMsg = (id: string) => {
        toastAlerts.value = toastAlerts.value.filter(
            (alert) => alert.id !== id,
        );
    };

    /**
     * Auto delete message after 15 seconds
     */
    const setToastTimeout = (id: string) => {
        setTimeout(() => {
            removeToastMsg(id);
        }, 5000);
    };

    return {
        flashAlerts: readonly(flashAlerts),
        pushFlashAlert,
        toastAlerts: readonly(toastAlerts),
        pushToastAlert,
        removeToastMsg,
    };
};
