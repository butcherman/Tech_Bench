import axios from "axios";
import { okModal } from "../okModal.module";

export const performCustomerSearch = async (searchParam: customerSearch) => {
    const results = {
        data: [],
        currentPage: 0,
        numPages: 0,
        listFrom: 0,
        listTo: 0,
        listTotal: 0,
        pageArr: [1],
    };

    await axios
        .post(route("customers.search"), searchParam)
        .then((res) => {
            results.data = res.data.data;
            results.listFrom = res.data.from;
            results.listTo = res.data.to;
            results.listTotal = res.data.total;
            results.numPages = res.data.last_page;
            results.currentPage = res.data.current_page;
            results.pageArr = buildPageArr(
                res.data.current_page,
                res.data.last_page
            );
        })
        .catch(() => {
            okModal(
                "Unable to process request at this time.  Please try again later"
            );
        });

    return results;
};

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
