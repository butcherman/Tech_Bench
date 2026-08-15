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
): Promise<AxiosResponse<TResponse> | undefined> => {
    activeRequests.value++;

    try {
        return await callback();
    } catch (error) {
        if (axios.isAxiosError(error)) {
            if (error.response?.status === 422) {
                throw new ValidationError(
                    error.response.data.errors,
                    error.response.data.message,
                );
            }

            if (handleError) {
                // handleAxiosError(error);
                console.log(error);
            } else {
                throw error;
            }
        } else {
            throw error;
        }
    } finally {
        activeRequests.value--;
    }

    return undefined;
};

/**
 * Add Echo Socket ID to request
 */
// api.interceptors.request.use((config) => {
//     // const socketId = Echo.socketId();

//     // if (socketId) {
//     //     config.headers["X-Socket-Id"] = socketId;
//     // }

//     // return config;
// });

/**
 * GET request
 */
export const dataGet = <TResponse = unknown>(
    url: string,
    config?: AxiosRequestConfig,
) => request<TResponse>(() => api.get<TResponse>(url, config));

/**
 * POST request
 */
export const dataPost = <TResponse = unknown, TData = unknown>(
    url: string,
    data: TData,
    config?: AxiosRequestConfig,
) => request<TResponse>(() => api.post<TResponse>(url, data, config));

/**
 * PUT request
 */
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

/**
 * DELETE request
 */
export const dataDelete = <TResponse = unknown>(
    url: string,
    config?: AxiosRequestConfig,
) => request<TResponse>(() => api.delete<TResponse>(url, config));
