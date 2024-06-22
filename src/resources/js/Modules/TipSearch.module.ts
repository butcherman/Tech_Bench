/**
 * All data for performing a Tech Tip Search
 */

import axios from "axios";
import okModal from "./okModal";
import { ref, reactive } from "vue";

/*******************************************************************************
 *                        Typescript Interfaces                                *
 *******************************************************************************/
interface searchParams {
    searchFor: string;
    typeList: number[];
    equipList: number[];
    page: number;
    perPage: number;
}

interface paginationData {
    currentPage: number;
    totalPages: number;
    listFrom: number;
    listTo: number;
    listTotal: number;
    pageArr: number[];
}

interface axiosSearchResults {
    data: {
        current_page: number;
        data: never[]; // TODO - Type me
        first_page_url: string;
        from: number;
        last_page: number;
        last_page_url: string;
        next_page_url: string;
        path: string;
        per_page: number;
        prev_page_url: string;
        to: number;
        total: number;
    };
}

/*******************************************************************************
 *                    Search and loading Parameters                            *
 *******************************************************************************/
export const isLoading = ref<boolean>(false);
export const isDirty = ref<boolean>(false);
export const searchResults = ref([]); // TODO - Type me
export const searchParams = ref({
    searchFor: "",
    typeList: [],
    equipList: [],
    page: 1,
    perPage: 25,
});

export const paginationData = reactive<paginationData>({
    currentPage: 1,
    totalPages: 1,
    listFrom: 0,
    listTo: 0,
    listTotal: 0,
    pageArr: [1],
});

/*******************************************************************************
 *                    Axios call to perform search                             *
 *******************************************************************************/
export const triggerSearch = async () => {
    isLoading.value = true;
    isDirty.value = true;

    console.log(searchParams.value);

    await axios
        .post(route("tech-tips.search"), searchParams)
        .then((res) => processResults(res))
        .catch(() =>
            okModal(
                "Unable to process request at this time.  Pleas try again later."
            )
        )
        .finally(() => (isLoading.value = false));
};

export const resetSearch = () => {
    console.log("trigger reset");
    searchParams.value.searchFor = "";
    searchParams.value.typeList = [];
    searchParams.value.equipList = [];
    searchParams.value.page = 1;
    searchParams.value.perPage = 25;
};

const processResults = (res: axiosSearchResults) => {
    console.log(res);
    // Assign results
    searchResults.value = res.data.data;
    // Build pagination footer
    paginationData.listFrom = res.data.from;
    paginationData.listTo = res.data.to;
    paginationData.listTotal = res.data.total;
    paginationData.totalPages = res.data.last_page;
    paginationData.currentPage = res.data.current_page;
};
