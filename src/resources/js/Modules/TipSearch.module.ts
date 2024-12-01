/**
 * All data for performing a Tech Tip Search
 */

import axios from "axios";
import okModal from "./okModal";
import { ref, reactive } from "vue";

import type { AxiosResponse } from "axios";

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
    data: techTip[];
    meta: {
        current_page: number;
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
export const searchResults = ref<techTip[]>([]);
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

    await axios
        .post(route("tech-tips.search"), searchParams.value)
        .then((res: AxiosResponse<axiosSearchResults>) => processResults(res))
        .catch(() =>
            okModal(
                "Unable to process request at this time.  Pleas try again later."
            )
        )
        .finally(() => (isLoading.value = false));
};

export const triggerPublicSearch = async () => {
    isLoading.value = true;
    isDirty.value = true;

    await axios
        .post(route("publicTips.search"), searchParams.value)
        .then((res: AxiosResponse<axiosSearchResults>) => processResults(res))
        .catch(() =>
            okModal(
                "Unable to process request at this time.  Pleas try again later."
            )
        )
        .finally(() => (isLoading.value = false));
};

export const resetSearch = () => {
    searchParams.value.searchFor = "";
    searchParams.value.typeList = [];
    searchParams.value.equipList = [];
    searchParams.value.page = 1;
    searchParams.value.perPage = 25;
};

/*******************************************************************************
 *                    Work with returned results                               *
 *******************************************************************************/
const processResults = (res: AxiosResponse<axiosSearchResults>) => {
    console.log(res);

    // Assign results
    searchResults.value = res.data.data;

    // Build pagination footer
    paginationData.listFrom = res.data.meta.from;
    paginationData.listTo = res.data.meta.to;
    paginationData.listTotal = res.data.meta.total;
    paginationData.totalPages = res.data.meta.last_page;
    paginationData.currentPage = res.data.meta.current_page;
};

/*******************************************************************************
 *                    Navigate through pagination                              *
 *******************************************************************************/
export const goToPage = (pageNumber: number) => {
    searchParams.value.page = pageNumber;
    triggerSearch();
};
