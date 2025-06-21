import { AxiosResponse } from "axios";
import { dataPost } from "../axiosWrapper.module";

interface idResponse {
    in_use: boolean;
    cust_name?: string | null;
    route?: string | null;
    disabled?: boolean;
    customer?: customer;
}

/**
 * Axios call to check if an int is taken by a customer as its ID
 */
export const checkCustId = async (custId: any): Promise<idResponse> => {
    if (custId) {
        return await dataPost(route("customers.search"), {
            cust_id: custId,
        }).then((res: void | AxiosResponse<any, customer>) => {
            if (res) {
                return {
                    in_use: res.data.cust_id ? true : false,
                    cust_name: res.data.name ?? null,
                    customer: res.data,
                    route: res.data
                        ? route("customers.show", res.data.slug)
                        : null,
                    disabled: res.data.deleted_at ? true : false,
                };
            }

            return {
                in_use: false,
            };
        });
    }

    return {
        in_use: false,
    };
};
