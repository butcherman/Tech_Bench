import Vue from 'vue';
import { ValidationProvider, ValidationObserver, extend } from 'vee-validate';
import { required, email, confirmed, min } from 'vee-validate/dist/rules';

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


Vue.component('ValidationProvider', ValidationProvider);
Vue.component('ValidationObserver', ValidationObserver);
