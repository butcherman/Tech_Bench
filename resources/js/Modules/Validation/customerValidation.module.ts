import * as yup from 'yup';

export const customerValidation = {
    cust_id  : yup.number().nullable(),
    parent_id: yup.string().nullable(),
    name     : yup.string().required().label('Customer Name'),
    dba_name : yup.string().nullable(),
    address  : yup.string().required().label('Customer Address'),
    city     : yup.string().required().label('Customer City'),
    state    : yup.string().required().label('Customer State'),
    zip      : yup.number().required().label('Customer Zip Code'),
};
