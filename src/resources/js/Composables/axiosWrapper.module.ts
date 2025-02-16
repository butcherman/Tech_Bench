import errModal from "@/Modules/errModal";
import axios, { AxiosError, AxiosResponse } from "axios";
import { ref } from "vue";

/*
|-------------------------------------------------------------------------------
| Loading State for Axios Request
|-------------------------------------------------------------------------------
*/
export const isLoading = ref<boolean>(false);

/*
|-------------------------------------------------------------------------------
| Axios GET request
|-------------------------------------------------------------------------------
*/
export async function dataGet(url: string) {
    isLoading.value = true;

    return await axios
        .get(url)
        .then((res) => {
            return res;
        })
        .catch((err) => handleError(err))
        .finally(() => (isLoading.value = false));
}

/*
|-------------------------------------------------------------------------------
| Axios POST request
|-------------------------------------------------------------------------------
*/
export async function dataPost(
    url: string,
    params: any
): Promise<void | AxiosResponse<any, any>> {
    isLoading.value = true;

    return await axios
        .post(url, params)
        .then((res) => {
            return res;
        })
        .catch((err) => handleError(err))
        .finally(() => (isLoading.value = false));
}

/*
|-------------------------------------------------------------------------------
| Handle any errors
|-------------------------------------------------------------------------------
*/
function handleError(err: AxiosError) {
    errModal(err.status ?? 0, err.message);
}
