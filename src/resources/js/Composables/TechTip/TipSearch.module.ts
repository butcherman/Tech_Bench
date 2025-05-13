import { reactive, ref } from "vue";
import { dataPost, isLoading } from "../axiosWrapper.module";
import type { AxiosResponse } from "axios";

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
    typeList: [],
    equipList: [],
    page: 1,
    perPage: perPageDefault.value,
});

export const searchResults = ref<techTip[]>([]);
export const paginationData = reactive<paginationData>({
    currentPage: 1,
    totalPages: 1,
    listFrom: 0,
    listTo: 0,
    listTotal: 0,
    pageArr: [1],
});

/**
 * Fetch data for Tech Tip Search.
 */
export const triggerSearch = (): void => {
    console.log("trigger search");

    isDirty.value = true;

    dataPost(route("tech-tips.search"), searchParams).then((res) =>
        console.log(res)
    );
};

/**
 * Reset the Search Parameters back to default values.
 */
export const resetSearch = (): void => {
    searchParams.searchFor = "";
    searchParams.typeList = [];
    searchParams.equipList = [];
    searchParams.page = 1;
    isDirty.value = false;
};
