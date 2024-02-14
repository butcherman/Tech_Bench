import axios from "axios";
import { ref, reactive } from "vue";
import okModal from "./okModal";

/*******************************************************************************
 *                        Typescript Interfaces                                *
 *******************************************************************************/
interface searchParams {
    basic: boolean;
    searchFor: string;
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
        data: customer[];
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
    }
}

/*******************************************************************************
 *                    Search and loading Parameters                            *
 *******************************************************************************/
export const isLoading = ref<boolean>(false);
export const showSiteList = ref<boolean>(true);
export const searchResults = ref<customer[]>([]);
export const searchParams = reactive<searchParams>({
    basic: false,
    searchFor: "",
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

    await axios
        .post(route("customers.search"), searchParams)
        .then((res) => processResults(res))
        .catch(() =>
            okModal(
                "Unable to process request at this time.  Pleas try again later."
            )
        )
        .finally(() => (isLoading.value = false));
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
