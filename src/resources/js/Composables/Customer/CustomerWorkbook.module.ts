import { computed, ref } from "vue";
import { isLoading } from "../axiosWrapper.module";

export const activePage = ref<string>("0");

const localLoad = ref<boolean>(false);
const hasError = ref<boolean>(false);

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
