import { upperCase } from "lodash";

type sortOrder = "asc" | "desc";

export function sortDataObject<T>(
    sortArray: T[],
    sortOrder: sortOrder,
    sortField: keyof T
) {
    // Take a valid data sample (no null values)
    let testData = sortArray.find(
        (data) => data[sortField] != undefined && data[sortField] != null
    );

    // Determine what type of data we are working with
    if (testData && typeof testData[sortField] === "boolean") {
        return sortBooleanObject(sortArray, sortOrder, sortField);
    }

    return sortStringObject(sortArray, sortOrder, sortField);
}

/**
 * Sort String Objects
 */
function sortStringObject<T>(
    sortArray: T[],
    sortOrder: sortOrder,
    sortField: keyof T
) {
    if (sortOrder === "asc") {
        return sortStringObjectAsc(sortArray, sortField);
    }

    return sortStringObjectDesc(sortArray, sortField);
}

/**
 * Sort a-z
 */
function sortStringObjectAsc<T>(sortArray: T[], sortField: keyof T) {
    sortArray.sort((a, b) => {
        const aVal = upperCase(a[sortField] as string);
        const bVal = upperCase(b[sortField] as string);

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
}

/**
 * Sort z-a
 */
function sortStringObjectDesc<T>(sortArray: T[], sortField: keyof T) {
    sortArray.sort((a: T, b: T) => {
        const aVal = upperCase(a[sortField] as string);
        const bVal = upperCase(b[sortField] as string);

        if (aVal < bVal) {
            return 1;
        }

        if (aVal > bVal) {
            return -1;
        }

        return 0;
    });

    return sortArray;
}

/**
 * Sort Boolean objects
 */
function sortBooleanObject<T>(
    sortArray: T[],
    sortOrder: sortOrder,
    sortField: keyof T
): T[] {
    if (sortOrder === "asc") {
        return sortBooleanObjectAsc(sortArray, sortField);
    }

    return sortBooleanObjectDesc(sortArray, sortField);
}

/**
 * Sort True on top
 */
function sortBooleanObjectAsc<T>(sortArray: T[], sortField: keyof T): T[] {
    return sortArray.sort((a: T, b: T) => {
        const aVal = a[sortField];
        const bVal = b[sortField];

        return aVal === bVal ? 0 : aVal ? -1 : 1;
    });
}

/**
 * Sort False on top
 */
function sortBooleanObjectDesc<T>(sortArray: T[], sortField: keyof T): T[] {
    return sortArray.sort((a: T, b: T) => {
        const aVal = a[sortField];
        const bVal = b[sortField];

        return aVal === bVal ? 0 : aVal ? 1 : -1;
    });
}
