import { dataGet } from "../axiosWrapper.module";

/**
 * Axios call to check if an int is taken by a customer as its ID
 */
export const checkCustId = async (custId: any) => {
    if (custId) {
        return await dataGet(route("customer.check-id", custId)).then(
            (res) => res.data
        );
    }

    return {
        in_use: false,
        cust_name: null,
        route: null,
    };
};
