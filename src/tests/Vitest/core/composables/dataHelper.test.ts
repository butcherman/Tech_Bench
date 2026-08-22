import { useDataHelper } from "@/core/composables/dataHelper";

describe("useDataHelper", () => {
    describe("indexData", () => {
        test("indexes each item with an id and position flags", () => {
            const originalData = [
                { name: "John", age: 25 },
                { name: "Jane", age: 30 },
                { name: "Bob", age: 35 },
            ];

            const { indexData } = useDataHelper();
            const indexed = indexData(originalData);

            expect(indexed).toHaveLength(3);

            expect(indexed[0]).toEqual({
                id: expect.any(String),
                isFirst: true,
                isLast: false,
                data: originalData[0],
            });

            expect(indexed[1]).toEqual({
                id: expect.any(String),
                isFirst: false,
                isLast: false,
                data: originalData[1],
            });

            expect(indexed[2]).toEqual({
                id: expect.any(String),
                isFirst: false,
                isLast: true,
                data: originalData[2],
            });
        });

        test("generates a unique id for each item", () => {
            const originalData = [
                { name: "John" },
                { name: "Jane" },
                { name: "Bob" },
                { name: "Alice" },
            ];

            const { indexData } = useDataHelper();
            const indexed = indexData(originalData);
            const ids = indexed.map((item) => item.id);

            expect(new Set(ids).size).toBe(ids.length);
        });

        test("preserves the original data", () => {
            const originalData = [
                { name: "John", age: 25 },
                { name: "Jane", age: 30 },
            ];

            const { indexData } = useDataHelper();
            const indexed = indexData(originalData);

            expect(indexed[0].data).toBe(originalData[0]);
            expect(indexed[1].data).toBe(originalData[1]);
        });

        test("returns an empty array when given empty data", () => {
            const { indexData } = useDataHelper();
            const indexed = indexData([]);

            expect(indexed).toEqual([]);
        });

        test("marks a single item as both first and last", () => {
            const originalData = [{ name: "John" }];

            const { indexData } = useDataHelper();
            const indexed = indexData(originalData);

            expect(indexed).toHaveLength(1);
            expect(indexed[0].isFirst).toBe(true);
            expect(indexed[0].isLast).toBe(true);
        });
    });
});

describe("getIndexedChunk", () => {
    const originalData = [
        { id: 1, name: "One" },
        { id: 2, name: "Two" },
        { id: 3, name: "Three" },
        { id: 4, name: "Four" },
        { id: 5, name: "Five" },
    ];

    test("returns the first page", () => {
        const { getIndexedChunk } = useDataHelper();

        const result = getIndexedChunk(originalData, 1, 2);

        expect(result).toHaveLength(2);
        expect(result[0].data).toBe(originalData[0]);
        expect(result[1].data).toBe(originalData[1]);
    });

    test("returns a middle page", () => {
        const { getIndexedChunk } = useDataHelper();

        const result = getIndexedChunk(originalData, 2, 2);

        expect(result).toHaveLength(2);
        expect(result[0].data).toBe(originalData[2]);
        expect(result[1].data).toBe(originalData[3]);
    });

    test("returns the final partial page", () => {
        const { getIndexedChunk } = useDataHelper();

        const result = getIndexedChunk(originalData, 3, 2);

        expect(result).toHaveLength(1);
        expect(result[0].data).toBe(originalData[4]);
        expect(result[0].isFirst).toBe(true);
        expect(result[0].isLast).toBe(true);
    });

    test("returns an empty array when the page is beyond the data", () => {
        const { getIndexedChunk } = useDataHelper();

        const result = getIndexedChunk(originalData, 10, 2);

        expect(result).toEqual([]);
    });

    test("indexes items relative to the chunk", () => {
        const { getIndexedChunk } = useDataHelper();

        const result = getIndexedChunk(originalData, 2, 2);

        expect(result[0].isFirst).toBe(true);
        expect(result[0].isLast).toBe(false);

        expect(result[1].isFirst).toBe(false);
        expect(result[1].isLast).toBe(true);
    });

    test("does not modify the original array", () => {
        const { getIndexedChunk } = useDataHelper();

        const originalCopy = [...originalData];

        getIndexedChunk(originalData, 2, 2);

        expect(originalData).toEqual(originalCopy);
    });
});
