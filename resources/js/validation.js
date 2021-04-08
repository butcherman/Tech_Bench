import Vue from 'vue';
import { ValidationProvider, ValidationObserver, extend } from 'vee-validate';
import { Validator } from 'vee-validate';
import { required, email, confirmed, min, numeric } from 'vee-validate/dist/rules';

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

extend('no-special', value => {
    const valid = new RegExp(/^[a-zA-Z0-9_ ]*$/);

    if(valid.test(value))
    {
        return true;
    }

    return 'Special characters are not allowed';
});

extend('unique-customer', value => {
    // const valid = () => { return window.axios.post('/customers/check-id', {cust_id: value}).then(data => data) };

    // var valid;
    // const valid = () => { return window.axios.post('/customers/check-id', {cust_id: value}).then(({res}) =>{ return res })};

    // const isUnique = (value) => {
    //     this.validating = true

    //     return Auth.checkDomain({fqdn: value}).then(({data}) => {
    //       this.validating = false
    //       return data

    //     })
    //     .catch(error => {
    //       this.validating = false
    //     })
    //   }

    // console.log(valid);
//TODO - Make this work!!!!!!!!!!!!!!!!!!!!!!!!!!
    // if(valid.name)
    // {
    //     return 'This Customer ID is taken by '+valid.name;
    // }

    return true;
});

Vue.component('ValidationProvider', ValidationProvider);
Vue.component('ValidationObserver', ValidationObserver);
