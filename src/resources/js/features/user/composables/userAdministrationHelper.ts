import { textColumn } from "@/features/dataTable/columns/textColumn";

export const useUserAdministrationHelper = () => {
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

    return {
        userTableColumns,
    };
};
