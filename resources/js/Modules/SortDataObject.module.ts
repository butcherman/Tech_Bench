import { upperCase } from "lodash";

type sortArray = object;
type sortOrder = "asc" | "desc";

export const sortDataObject = (
    sortArray: sortArray[],
    sortOrder: sortOrder,
    sortField: string
) => {
    // Take a valid data sample (no null values)
    let testData = sortArray.find(
        (data) =>
            data[sortField as keyof sortArray] != undefined &&
            data[sortField as keyof sortArray] != null
    );

    // Determine what type of data we are working with
    if (
        testData &&
        typeof testData[sortField as keyof sortArray] === "boolean"
    ) {
        return sortBooleanObject(sortArray, sortOrder, sortField);
    }

    return sortStringObject(sortArray, sortOrder, sortField);
};

/**
 * Sort String Objects
 */
const sortStringObject = (
    sortArray: sortArray[],
    sortOrder: sortOrder,
    sortField: string
) => {
    if (sortOrder === "asc") {
        return sortStringObjectAsc(sortArray, sortField);
    }

    return sortStringObjectDesc(sortArray, sortField);
};

/**
 * Sort a-z
 */
const sortStringObjectAsc = (sortArray: sortArray[], sortField: string) => {
    sortArray.sort((a, b) => {
        const aVal = upperCase(a[sortField as keyof sortArray]);
        const bVal = upperCase(b[sortField as keyof sortArray]);

        if (aVal === undefined || aVal === null) {
            return -1;
        }

        if (aVal < bVal) {
            return -1;
        }

        if (aVal > bVal) {
            return 1;
        }

        return 0;
    });

    return sortArray;
};

/**
 * Sort z-a
 */
const sortStringObjectDesc = (sortArray: sortArray[], sortField: string) => {
    sortArray.sort((a, b) => {
        const aVal = upperCase(a[sortField as keyof sortArray]);
        const bVal = upperCase(b[sortField as keyof sortArray]);

        if (aVal < bVal) {
            return 1;
        }

        if (aVal > bVal) {
            return -1;
        }

        return 0;
    });

    return sortArray;
};

/**
 * Sort Boolean objects
 */
const sortBooleanObject = (
    sortArray: sortArray[],
    sortOrder: sortOrder,
    sortField: string
) => {
    console.log("boolean object");

    if (sortOrder === "asc") {
        return sortBooleanObjectAsc(sortArray, sortField);
    }

    return sortBooleanObjectDesc(sortArray, sortField);
};

/**
 * Sort True on top
 */
const sortBooleanObjectAsc = (sortArray: sortArray[], sortField: string) => {
    sortArray.sort((a, b) => {
        const aVal = a[sortField as keyof sortArray];
        const bVal = b[sortField as keyof sortArray];

        return aVal === bVal ? 0 : aVal ? -1 : 1;
    });

    return sortArray;
};

/**
 * Sort False on top
 */
const sortBooleanObjectDesc = (sortArray: sortArray[], sortField: string) => {
    sortArray.sort((a, b) => {
        const aVal = a[sortField as keyof sortArray];
        const bVal = b[sortField as keyof sortArray];

        return aVal === bVal ? 0 : aVal ? 1 : -1;
    });
};
