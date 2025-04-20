import { reactive, ref } from "vue";
import { dataPost, isLoading } from "../axiosWrapper.module";
import { AxiosResponse } from "axios";

interface searchParams {
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

/*
|-------------------------------------------------------------------------------
| Search and Loading Parameters
|-------------------------------------------------------------------------------
*/
export { isLoading };
export const isDirty = ref<boolean>(false);
export const showSites = ref<boolean>(true);
const perPageDefault = ref<number>(25);

export const searchParams = reactive<searchParams>({
    searchFor: "",
    page: 1,
    perPage: perPageDefault.value,
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

export const setPerPageDefault = (perPage: number): void => {
    perPageDefault.value = perPage;
    searchParams.perPage = perPage;
};

/**
 * Fetch data for Customer Search
 */
export const triggerSearch = (): void => {
    isDirty.value = true;

    dataPost(route("customers.search"), searchParams).then((res) =>
        processResults(res)
    );
};

/**
 * Set all search parameters back to their default values.
 */
export const resetSearch = (): void => {
    searchParams.searchFor = "";
    searchParams.page = 1;
    isDirty.value = false;
};

/**
 * Assign the results and build out the pagination footer.
 */
const processResults = (res: void | AxiosResponse<any, customer>): void => {
    if (res) {
        // Assign results
        searchResults.value = res.data.data;

        // Build pagination footer
        paginationData.listFrom = res.data.from;
        paginationData.listTo = res.data.to;
        paginationData.listTotal = res.data.total;
        paginationData.totalPages = res.data.last_page;
        paginationData.currentPage = res.data.current_page;
    }
};
