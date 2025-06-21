/**
 * Break an array down into smaller chunks for paginating.
 */
export const paginated = <T>(
    originalArray: T[],
    perPage: number = 10
): T[][] => {
    let chunked = [];

    for (let i = 0; i < originalArray.length; i += perPage) {
        chunked.push(originalArray.slice(i, i + perPage));
    }

    return chunked;
};
