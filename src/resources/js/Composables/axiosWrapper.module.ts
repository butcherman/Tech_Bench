import axios, { AxiosResponse } from "axios";
import { ref } from "vue";

export const isLoading = ref<boolean>(false);

export async function dataGet(
    url: string
): Promise<void | AxiosResponse<any, any>> {
    isLoading.value = true;

    return await axios
        .get(url)
        .then((res) => res)
        .catch((err) => alert(err))
        .finally(() => (isLoading.value = false));
}

export async function dataPost(
    url: string,
    args: any
): Promise<void | AxiosResponse<any, any>> {
    isLoading.value = true;

    return await axios
        .post(url, args)
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

            alert(err);
        })
        .finally(() => (isLoading.value = false));
}
