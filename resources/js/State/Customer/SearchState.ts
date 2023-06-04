import axios from "axios";
import { ref, reactive } from "vue";
import { okModal } from "@/Modules/okModal.module";

export const loading = ref(false);
export const searchResults = ref<customer[]>([]);

/**
 * Axios call to search for customers
 */
export const triggerSearch = async () => {
    loading.value = true;

    await axios
        .post(route("customers.search"), searchParam)
        .then((res) => {
            //  Assign search Results
            searchResults.value = res.data.data;
            //  Build pagination footer
            paginationData.listFrom = res.data.from;
            paginationData.listTo = res.data.to;
            paginationData.listTotal = res.data.total;
            paginationData.numPages = res.data.last_page;
            paginationData.currentPage = res.data.current_page;
            paginationData.pageArr = buildPageArr(
                res.data.current_page,
                res.data.last_page
            );
        })
        .catch(() => {
            okModal(
                "Unable to process request at this time.  Please try again later"
            );
        })
        .finally(() => (loading.value = false));
};

/**
 * Clear all search parameters
 */
export const resetSearch = () => {
    searchParam.name = "";
    searchParam.city = "";
    searchParam.equip = null;
    searchParam.page = 1;

    triggerSearch();
};

/**
 * Search Parameters that are sent to server to perform customer search
 */
export const searchParam = reactive<customerSearchParam>({
    //  Search data
    name: "",
    city: "",
    equip: null,
    //  Pagination and sort parameters
    page: 1,
    perPage: 25,
    sortField: "name",
    sortType: "asc",
});

/**
 * Pagination Data for moving through search results
 */
export const paginationData = reactive<customerPagination>({
    currentPage: 1,
    numPages: 1,
    listFrom: 0,
    listTo: 0,
    listTotal: 0,
    pageArr: [1],
});
export const paginationArray: number[] = [25, 50, 100];

/**
 * Build the pagination links for the bottom of the table
 * We want a total of five pages showing, the active page should be in the
 * middle unless it is toward the front of end of the line
 */
const buildPageArr = (curPage: number, totalPages: number): number[] => {
    let pageArr = [];
    let start = totalPages > 5 ? curPage - 2 : 1;

    //  If start was going to be a negative number, we change it to 1
    if (start <= 0) {
        start = 1;
    }

    let end = totalPages > 5 ? start + 4 : totalPages;
    //  If end was going to be a higher number than the last page, we modify it
    if (end > totalPages) {
        end = totalPages;
        //  Try to still get five links in the array
        if (totalPages > 5) {
            start = totalPages - 4;
        }
    }

    for (let i = start; i <= end; i++) {
        pageArr.push(i);
    }

    return pageArr;
};
