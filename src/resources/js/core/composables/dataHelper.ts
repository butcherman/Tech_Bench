import { useId } from "vue";

export const useDataHelper = () => {
    /**
     * Rewrite the data to include a unique identifier to be used in indexing
     */
    const indexData = <TData>(originalData: TData[]): IndexedData<TData>[] => {
        return originalData.map((data: TData) => {
            return {
                id: useId(),
                data,
            };
        });
    };

    return {
        indexData,
    };
};
