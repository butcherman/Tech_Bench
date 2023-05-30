import { object, addMethod, array, string } from "yup";

/**
 * For an array of values, validate that at least one entry has a value
 */
addMethod(array, 'minOne', function(errMsg:string) {
    return this.test('min-one', errMsg, function(value) {
        if(value && value.length)
        {
            let passed = false;
            value.forEach((elem:string) => { if(elem.length) passed = true});

            return passed;
        }

        return false;
    });
});

/**
 * For an array of values, validate that there are no duplicates
 */
addMethod(array, 'noDuplicates', function(errMsg:string) {
    return this.test('no-duplicates', errMsg, function(value) {
        let duplicates = value?.filter((item, index) => value.indexOf(item) != index)

        return duplicates?.length === 0;
    });
});

export const equipmentValidator = object({
    category: string().required(),
    name    : string().required('Please enter a name for the Equipment'),
    custData: array().minOne('You must provide information to gather for customers')
                .noDuplicates('Duplicates entries are not allowed'),
});
