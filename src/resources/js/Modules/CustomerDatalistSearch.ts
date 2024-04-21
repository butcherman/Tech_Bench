import axios from "axios";
import { ref } from "vue";

/**
 * Dynamically fetch customer datalist information
 */
let delayTimer: ReturnType<typeof setTimeout>;

export const customerDatalist = ref<customer[]>([]);
export const fetchDatalist = async (searchString: string) => {
    // Delay 1/2 second to allow additional typing
    clearTimeout(delayTimer);

    let searchData = {
        basic: true,
        searchFor: searchString,
    };

    delayTimer = setTimeout(() => {
        console.log("searching");
        axios.post(route("customers.search"), searchData).then((res) => {
            console.log(res.data);
            // Populate the datalist for users to select from
            customerDatalist.value = res.data;
        });
    }, 500);
};
