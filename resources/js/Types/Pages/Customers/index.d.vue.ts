type customerSearchParam = {
    name: string | null;
    city: string | null;
    equip: string | null;
    page: number;
    perPage: number;
    sortField: string;
    sortType: "asc" | "desc";
};

type customerSearch = {
    data: customer[];
    currentPage: number;
    numPages: number;
    listFrom: number;
    listTo: number;
    listTotal: number;
    pageArr: number[];
};

type customerPagination = {
    currentPage: number;
    numPages: number;
    listFrom: number;
    listTo: number;
    listTotal: number;
    pageArr: number[];
};

type customerSearchDataSymbol = {
    searchParam: customerSearchParam;
    paginationData: customerPagination;
    paginationArray: number[];
    triggerSearch: () => void;
    resetSearch: () => void;
}