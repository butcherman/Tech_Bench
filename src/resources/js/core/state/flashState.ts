import { readonly, ref } from "vue";
import { v4 } from "uuid";

const flashAlerts = ref<FlashAlert[]>([]);

export const useFlashState = () => {
    /**
     * Push a new Flash Alert into the flash queue
     */
    const pushFlashAlert = (flashAlert: FlashAlert): void => {
        // Make sure the message has an ID
        if (!Object.hasOwn(flashAlert, "id")) {
            flashAlert.id = v4();
        }

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

    return {
        flashAlerts: readonly(flashAlerts),
        pushFlashAlert,
    };
};
