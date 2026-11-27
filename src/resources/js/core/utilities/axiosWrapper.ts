import axios, { type AxiosRequestConfig, type AxiosResponse } from "axios";
import { computed, ref } from "vue";
import { ValidationError } from "../exceptions/validationError";

const activeRequests = ref(0);

export const isLoading = computed(() => activeRequests.value > 0);

const api = axios.create({
    headers: {
        Accept: "application/json",
    },
});

const request = async <TResponse>(
    callback: () => Promise<AxiosResponse<TResponse>>,
    handleError = true,
): Promise<TResponse | undefined> => {
    activeRequests.value++;

    try {
        const response = await callback();

        return response.data;
    } catch (error) {
        if (!axios.isAxiosError(error)) {
            throw error;
        }

        if (error.response?.status === 422) {
            throw new ValidationError(
                error.response.data.errors,
                error.response.data.message,
            );
        }

        if (!handleError) {
            throw error;
        }

        // handleAxiosError(error);
        console.log(error);

        return undefined;
    } finally {
        activeRequests.value--;
    }
};

export const dataGet = <TResponse = unknown>(
    url: string,
    config?: AxiosRequestConfig,
) => request<TResponse>(() => api.get<TResponse>(url, config));

export const dataPost = <TResponse = unknown, TData = unknown>(
    url: string,
    data: TData,
    config?: AxiosRequestConfig,
) => request<TResponse>(() => api.post<TResponse>(url, data, config));

export const dataPut = <TResponse = unknown, TData = unknown>(
    url: string,
    data: TData,
    handleError = true,
    config?: AxiosRequestConfig,
) =>
    request<TResponse>(
        () => api.put<TResponse>(url, data, config),
        handleError,
    );

export const dataDelete = <TResponse = unknown>(
    url: string,
    config?: AxiosRequestConfig,
) => request<TResponse>(() => api.delete<TResponse>(url, config));
