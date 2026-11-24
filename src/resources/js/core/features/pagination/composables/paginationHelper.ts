import { computed } from "vue";

export const usePaginationHelper = (props: PaginationProps) => {
    /**
     * Total number of pages in the paginated data
     */
    const totalPages = computed(() =>
        Math.ceil(props.totalRecords / props.perPage),
    );

    /**
     * Index + 1 of the starting record for the currently visible page
     */
    const recordStart = computed(
        () => (props.currentPage - 1) * props.perPage + 1,
    );

    /**
     * Index + 1 of the ending record for the currently visible page
     */
    const recordEnd = computed(() =>
        Math.min(recordStart.value + props.perPage - 1, props.totalRecords),
    );

    /*
|-------------------------------------------------------------------------------
| Navigation go-to-page links for navigating to a specific page.
| Only five pages will show with the active page in the middle if more
| than five available.
|-------------------------------------------------------------------------------
*/
    const paginationArray = computed<number[]>(() => {
        let pageArr: number[] = [];
        let start: number = totalPages.value > 5 ? props.currentPage - 2 : 1;

        //  If start was going to be a negative number, we change it to 1
        if (start <= 0) {
            start = 1;
        }

        let end = totalPages.value > 5 ? start + 4 : totalPages.value;
        //  If end was going to be a higher number than the last page, we modify it
        if (end > totalPages.value) {
            end = totalPages.value;
            //  Try to still get five links in the array
            if (totalPages.value > 5) {
                start = totalPages.value - 4;
            }
        }

        for (let i = start; i <= end; i++) {
            pageArr.push(i);
        }

        return pageArr;
    });

    return {
        totalPages,
        recordStart,
        recordEnd,
        paginationArray,
    };
};
