import type { DataTableColumn } from "@/features/dataTable/types";

export const useUserAdministrationHelper = () => {
    /**
     * List of columns for any list of users
     */
    const userTableColumns: DataTableColumn<User>[] = [
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
    ];

    return {
        userTableColumns,
    };
};
