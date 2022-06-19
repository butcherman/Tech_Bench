import { defineStore } from "pinia";

/**
 * Customer Store holds and shares all of the customers information
 */
export const useCustomerStore = defineStore('customer', {
    state: () => {
        return {
            custDetails: {},
            equipment  : [],
            contacts   : [],
            notes      : [],

            userPerm   : {},
            isFav      : false,
        }
    },
    getters: {
        cust_id()
        {
            return this.custDetails.cust_id ? this.custDetails.cust_id : null;
        },
        allowShare()
        {
            if(this.custDetails.child_count > 0 || this.custDetails.parent_id)
            {
                return true;
            }

            return false;
        }
    },
    actions: {
        allowPermission(type, action)
        {
            return this.userPerm[type][action];
        }
    }
});
