import { readonly } from "vue";

export const useUserAdministration = () => {
    /**
     * List of columns for any list of users
     */
    const userTableColumns = [
        {
            label: "Name",
            field: "full_name",
            sort: true,
            filterable: true,
        },
        {
            label: "Username",
            field: "username",
            sort: true,
            filterable: true,
        },
        {
            label: "Email",
            field: "email",
            sort: true,
            filterable: true,
        },
        {
            label: "Role",
            field: "role_name",
            sort: true,
            filterable: true,
            filterSelect: true,
        },
        {
            field: "actions",
        },
    ];

    return {
        userTableColumns: readonly(userTableColumns),
    };
};
