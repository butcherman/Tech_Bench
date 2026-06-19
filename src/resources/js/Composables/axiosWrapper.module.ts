import errorModal from "@/Modules/errorModal";
import axios from "axios";
import { ref } from "vue";
import type { AxiosResponse } from "axios";

interface errorMessage {
    status: number | undefined;
    message: laravelValidationErrors | string;
}

const axiosConfig = {
    headers: {
        "X-Socket-Id": Echo.socketId(),
    },
};

export const isLoading = ref<boolean>(false);

export async function dataGet(
    url: string,
): Promise<void | AxiosResponse<any, any>> {
    isLoading.value = true;

    return await axios
        .get(url)
        .then((res) => res)
        .catch((err) => handleAxiosError(err))
        .finally(() => (isLoading.value = false));
}

export async function dataPost(
    url: string,
    args: any,
): Promise<void | AxiosResponse<any, any>> {
    isLoading.value = true;

    return await axios
        .post(url, args, axiosConfig)
        .then((res) => res)
        .catch((err) => {
            if (err.status === 422) {
                throw {
                    status: err.status,
                    message: {
                        errors: err.response.data.errors,
                        message: err.response.data.message,
                    },
                };
            }

            handleAxiosError(err);
        })
        .finally(() => (isLoading.value = false));
}

export async function dataPut(
    url: string,
    args: any,
    handleError: boolean = true,
): Promise<void | AxiosResponse<any, any>> {
    isLoading.value = true;

    return await axios
        .put(url, args, axiosConfig)
        .then((res) => res)
        .catch((err) => {
            if (err.status === 422 || !handleError) {
                throw {
                    status: err.status,
                    message: {
                        errors: err.response.data.errors,
                        message: err.response.data.message,
                    },
                };
            }

            handleAxiosError(err);
        })
        .finally(() => (isLoading.value = false));
}

export async function dataDelete(
    url: string,
): Promise<void | AxiosResponse<any, any>> {
    isLoading.value = true;

    return await axios
        .delete(url)
        .then((res) => res)
        .catch((err) => handleAxiosError(err))
        .finally(() => (isLoading.value = false));
}

export function handleAxiosError(errorData: errorMessage) {
    let errMessage: string;

    if (typeof errorData.message !== "string") {
        errMessage = errorData.message.message;
    } else {
        errMessage = errorData.message;
    }

    errorModal(errorData.status, errMessage);
}
