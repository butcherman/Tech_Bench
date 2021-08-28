import Vue from 'vue';
import { ValidationProvider, ValidationObserver, extend } from 'vee-validate';
import { required, email, confirmed, min, numeric } from 'vee-validate/dist/rules';
// import { Validator } from 'vee-validate';

/*
*   Imported rules
*/
extend('required', {
    ...required,
    message: 'This field is required',
});

extend('email', {
    ...email,
    message: 'Please enter a valid Email Address',
});

extend('confirmed', {
    ...confirmed,
    message: 'Please enter the same value below',
});

extend('min', {
    ...min,
    message: 'This field must have at least {length} characters',
});

extend('numeric', {
    ...numeric,
    message: 'Only numbers are allowed',
})

/*****************************************************************************************
 *                                    Custom Rules                                       *
 *****************************************************************************************/

/*
*   Only letters and numbers may be used
*/
extend('no-special', value => {
    const valid = new RegExp(/^[a-zA-Z0-9_ ]*$/);
    if(valid.test(value))
    {
        return true;
    }

    return 'Special characters are not allowed';
});

/*
*   Check to see if the customer ID already exists in the customers table
*/
extend('unique-customer', value => {
    return window.axios.post('/customers/check-id', {cust_id: value}).then(res => {
        var data = res.data;
        if(data.valid)
        {
            return true;
        }

        return data.data.message;
    });
});

Vue.component('ValidationProvider', ValidationProvider);
Vue.component('ValidationObserver', ValidationObserver);
