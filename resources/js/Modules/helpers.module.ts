/**
 * Move items around within an array
 */
export const array_move = (arr:any[], old_index:number, new_index:number):any[] => {
    if (new_index >= arr.length) {
        let i:number = new_index - arr.length + 1;
        while (i--) {
            arr.push(undefined);
        }
    }
    arr.splice(new_index, 0, arr.splice(old_index, 1)[0]);
    return arr;
};
