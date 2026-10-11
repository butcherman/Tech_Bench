export const useDataHelper = () => {
    /**
     * Rewrite the data to include a unique identifier to be used in indexing
     */
    const indexData = <TData>(originalData: TData[]): IndexedData<TData>[] => {
        return originalData.map((data: TData, index: number) => {
            return {
                id: Math.random().toString(36).substring(2, 11),
                isFirst: index === 0,
                isLast: index === originalData.length - 1,
                data,
            };
        });
    };

    /**
     * Return a specific chunk of data for pagination
     */
    const getIndexedChunk = <TData>(
        originalData: TData[],
        currentPage: number,
        perPage: number,
    ): IndexedData<TData>[] => {
        let startIndex = (currentPage - 1) * perPage;
        let endIndex = startIndex + perPage;

        return indexData(originalData.slice(startIndex, endIndex));
    };

    return {
        indexData,
        getIndexedChunk,
    };
};
