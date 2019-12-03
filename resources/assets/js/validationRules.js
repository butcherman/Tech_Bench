import {
    required,
    email,
    alpha_num,
} from 'vee-validate/dist/rules';
import { extend } from 'vee-validate';

extend('required', required);
extend('email', email);
extend('alpha_num', alpha_num);
