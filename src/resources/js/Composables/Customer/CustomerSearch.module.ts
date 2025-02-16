import { reactive, ref } from "vue";
import { dataPost, isLoading } from "../axiosWrapper.module";

interface customerSearchResults {
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
    };
}

interface paginationData {
    currentPage: number;
    totalPages: number;
    listFrom: number;
    listTo: number;
    listTotal: number;
    pageArr: number[];
}

interface searchParams {
    // basic: boolean;
    searchFor: string;
    page: number;
    perPage: number;
}

/*
|-------------------------------------------------------------------------------
| Search and Loading Parameters
|-------------------------------------------------------------------------------
*/
export { isLoading };
export const showSiteList = ref<boolean>(true);

export const searchParams = reactive<searchParams>({
    searchFor: "",
    page: 1,
    perPage: 25,
});

export const searchResults = ref<customer[]>([]);
export const paginationData = reactive<paginationData>({
    currentPage: 1,
    totalPages: 1,
    listFrom: 0,
    listTo: 0,
    listTotal: 0,
    pageArr: [1],
});

/*
|-------------------------------------------------------------------------------
| Perform Customer Search
|-------------------------------------------------------------------------------
*/
export const triggerSearch = (): void => {
    dataPost(route("customers.search"), searchParams).then((res) => {
        if (res) {
            processResults(res);
        }
    });
};

export const resetSearch = (): void => {
    searchParams.searchFor = "";
};

const processResults = (res: customerSearchResults): void => {
    searchResults.value = res.data.data;
    // Build pagination footer
    paginationData.listFrom = res.data.from;
    paginationData.listTo = res.data.to;
    paginationData.listTotal = res.data.total;
    paginationData.totalPages = res.data.last_page;
    paginationData.currentPage = res.data.current_page;
};
