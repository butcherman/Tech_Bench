import { object, number, string} from 'yup';

export const customerValidation = object({
    cust_id  : number().nullable(),
    parent_id: string().nullable(),
    name     : string().required().label('Customer Name'),
    dba_name : string().nullable(),
    address  : string().required().label('Customer Address'),
    city     : string().required().label('Customer City'),
    state    : string().required().label('Customer State'),
    zip      : number().required().label('Customer Zip Code'),
});
