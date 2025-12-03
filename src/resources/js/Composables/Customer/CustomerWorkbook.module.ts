import { computed, ref } from "vue";
import { dataGet, isLoading } from "../axiosWrapper.module";

export const activePage = ref<string>("0");
export const isPreview = ref<boolean>(false);
export const setWbHash = (hash: string): boolean => {
    wbHash.value = hash;

    return true;
};

const localLoad = ref<boolean>(false);
const hasError = ref<boolean>(false);
const wbHash = ref<string>();

/*
|-------------------------------------------------------------------------------
| Visual Save Status
|-------------------------------------------------------------------------------
*/
const wbLoading = computed<boolean>(() => isLoading.value || localLoad.value);

export const saveIcon = computed<string>(() => {
    if (hasError.value) {
        return "triangle-exclamation";
    }

    if (wbLoading.value) {
        return "spinner";
    }

    return "circle-check";
});

export const saveClass = computed<string>(() => {
    if (hasError.value) {
        return "text-danger";
    }

    if (wbLoading.value) {
        return "text-warning fa-spin";
    }

    return "text-success";
});

/*
|-------------------------------------------------------------------------------
| Fetch and Save Workbook Values
|-------------------------------------------------------------------------------
*/
export const fetchWbValues = () => {
    if (!isPreview.value && wbHash.value) {
        dataGet(route("cust-workbook.edit", wbHash.value)).then((res) =>
            console.log(res)
        );
    }
};
