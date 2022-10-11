<template>
    <Head title="User Settings" />
    <App>
        <h4 class="text-center text-md-start">User Settings</h4>
        <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Account</div>
                        <VueForm
                            :validation-schema="userAccount.validationSchema"
                            :initial-values="userAccount.initialValues"
                            submit-text="Update"
                            ref="accountForm"
                            @submit="updateAccount"
                        >
                            <TextInput
                                id="first-name"
                                name="first_name"
                                label="First Name"
                            />
                            <TextInput
                                id="last-name"
                                name="last_name"
                                label="Last Name"
                            />
                            <TextInput
                                id="email"
                                name="email"
                                label="Email Address"
                            />
                        </VueForm>
                    </div>
                </div>
            </div>
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Notifications</div>
                        <!-- <ValidationObserver v-slot="{handleSubmit}">
                            <b-overlay :show="submit.settings" class="h-100">
                                <template #overlay>
                                    <form-loader></form-loader>
                                </template>
                                <b-form @submit.prevent="handleSubmit(submitSettings)" novalidate class="h-100 d-flex flex-column">
                                    <div class="card-title">Settings</div>
                                    <b-form-checkbox
                                        v-for="(item, key) in settings"
                                        :key="key"
                                        v-model="userSettings[key].value"
                                        switch
                                    >
                                        {{item.user_setting_type.name}}
                                    </b-form-checkbox>
                                    <submit-button class="mt-auto" button_text="Update Notifications" :submitted="submit.settings" />
                                </b-form>
                            </b-overlay>
                        </ValidationObserver> -->
                    </div>
                </div>
            </div>
        </div>
    </App>
</template>

<script setup lang="ts">
    import App from '@/Layouts/app.vue';
    import VueForm from '@/Components/Base/VueForm.vue';
    import TextInput from '@/Components/Base/Input/TextInput.vue';
    import { ref, reactive } from 'vue';
    import { useForm } from '@inertiajs/inertia-vue3';
    import * as yup from 'yup';

    import type { userType } from '@/Types';

    const props = defineProps<{
        user: userType;
    }>();

    const accountForm = ref(null);
    const userAccount = reactive({
        initialValues: {
            first_name: props.user.first_name,
            last_name : props.user.last_name,
            email     : props.user.email,
        },
        validationSchema: {
            first_name: yup.string().required('First Name is Required'),
            last_name : yup.string().required('Last Name is Required'),
            email     : yup.string().required('Email Address is Required'),
        }
    });

    interface userAccountForm {
        first_name: string;
        last_name : string;
        email     : string;
    }

    function updateAccount(form:userAccountForm):void
    {
        console.log('update account', form);

        const accForm = useForm(form);
        accForm.put(route('settings.update', props.user.user_id), {
            onFinish: () => accountForm.value.endSubmit(),
        });
    }



    // import App from '../../Layouts/app';

    // export default {
    //     layout: App,
    //     props: {
    //         /**
    //          * user object - collection from /app/models/user
    //          */
    //         user: {
    //             type:     Object,
    //             required: true,
    //         },
    //         /**
    //          * filtered array from the /app/traits/usersettingstrait
    //          */
    //         settings: {
    //             type:     Array,
    //             required: true,
    //         }
    //     },
    //     data() {
    //         return {
    //             userData: {
    //                 first_name: this.user.first_name,
    //                 last_name:  this.user.last_name,
    //                 email:      this.user.email,
    //             },
    //             userSettings: this.settings,
    //             submit: {
    //                 userData: false,
    //                 settings: false,
    //             },
    //         }
    //     },
    //     methods: {
    //         /**
    //          * submit the user information form
    //          */
    //         submitUserData()
    //         {
    //             this.submit.userData = true;
    //             this.eventHub.$emit('clear-alert');
    //             this.$inertia.put(route('settings.update', this.user.user_id), this.userData, {
    //                 onFinish: () => {
    //                     this.submit.userData = false;
    //                     this.$refs['validation-observer'].reset();
    //                     if(Object.keys(this.$page.props.errors).length > 0)
    //                     {
    //                         this.eventHub.$emit('show-alert', {
    //                             message: 'Update Settings Failed.  Check form for additional information.',
    //                             type:    'danger',
    //                         });
    //                     }
    //                 }
    //             });
    //         },
    //         /**
    //          * submit the user settings form
    //          */
    //         submitSettings()
    //         {
    //             this.submit.settings = true;
    //             this.$inertia.post(route('settings.store'), {settingsData: this.userSettings, user_id: this.user.user_id}, {
    //                 onFinish: () => {
    //                     this.submit.settings = false
    //                 }
    //             });
    //         }
    //     },
    //     metaInfo: {
    //         title: 'User Settings',
    //     }
    // }
</script>
