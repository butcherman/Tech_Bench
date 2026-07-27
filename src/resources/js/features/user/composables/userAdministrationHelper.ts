import verifyModal from "@/core/composables/verifyModal.js";
import { textColumn } from "@/features/dataTable/columns/textColumn";
import { reset } from "@/wayfinder/routes/admin/user/two-factor/index.js";
import { router } from "@inertiajs/vue3";
import { useFlashState } from "@/core/state/flashState.js";
import {
    passwordLink,
    destroy,
    restore,
} from "@/wayfinder/routes/admin/user/index.js";

export const useUserAdministrationHelper = () => {
    const { pushFlashAlert } = useFlashState();

    /**
     * List of columns for any list of users
     */
    const userTableColumns = [
        textColumn<User>("full_name", "Name"),
        textColumn<User>("username", "Username"),
        textColumn<User>("email", "Email"),
        textColumn<User>("role_name", "Role", {
            filterSelect: true,
        }),
    ];

    /**
     * Remove all 2FA settings and devices so user has to setup again.
     */
    const resetTwoFa = (user: User, allowSaveDevice: boolean = false): void => {
        // verifyModal('Two Factor Settings will be reset and all ')

        let verifyMessage = "Two Factor Settings will be reset";
        if (allowSaveDevice) {
            verifyMessage += " and all Saved Devices will be deleted";
        }

        verifyModal(verifyMessage).then((res) => {
            if (res) {
                console.log("go");
                router.put(reset.url(user.username));
            }
        });
    };

    /**
     * Email a link to the user to allow them to reset their own password.
     */
    const sendResetEmail = (user: User): void => {
        router.post(
            passwordLink.url(),
            { email: user.email },
            {
                onSuccess: () =>
                    pushFlashAlert({
                        message: "Rese Email Sent",
                        level: "success",
                    }),
            },
        );
    };

    /**
     * Disable User and log them out of all sessions.
     */
    const disableUser = (user: User): void => {
        verifyModal(`${user.full_name} will be immediately disabled`).then(
            (res) => {
                if (res) {
                    router.delete(destroy.url(user.username));
                }
            },
        );
    };

    /**
     * Restore a Disabled user
     */
    const restoreUser = (user: User): void => {
        verifyModal(`${user.full_name} will be immediately enabled`).then(
            (res) => {
                if (res) {
                    router.get(restore.url(user.username));
                }
            },
        );
    };

    return {
        userTableColumns,
        resetTwoFa,
        sendResetEmail,
        disableUser,
        restoreUser,
    };
};
