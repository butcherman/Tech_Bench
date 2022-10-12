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
                    <div class="card-body d-flex flex-column">
                        <div class="card-title">Notifications</div>
                        <VueForm
                            :validation-schema="{}"
                            :initial-values="notifications.initialValues"
                            submit-text="Update"
                            ref="notifForm"
                            class="d-flex flex-column flex-grow-1"
                            @submit="updateNotifications"
                        >
                            <div v-for="(item, key) in settings">
                                <CheckboxSwitch
                                    :id="`notif-${key}`"
                                    :name="`type_id_${item.setting_type_id}`"
                                    :label="item.user_setting_type.name"
                                />
                            </div>
                        </VueForm>
                    </div>
                </div>
            </div>
        </div>
    </App>
</template>

<script setup lang="ts">
    import App               from '@/Layouts/app.vue';
    import VueForm           from '@/Components/Base/VueForm.vue';
    import TextInput         from '@/Components/Base/Input/TextInput.vue';
    import CheckboxSwitch    from '@/Components/Base/Input/CheckboxSwitch.vue';
    import { ref, reactive } from 'vue';
    import { useForm }       from '@inertiajs/inertia-vue3';
    import * as yup          from 'yup';

    import type { userType, settingsType } from '@/Types';

    const props = defineProps<{
        user    : userType;
        settings: settingsType[];
    }>();

    /**
     * User Account Settings Section
     */
    const accountForm = ref<InstanceType<typeof VueForm> | null>(null);
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

    const updateAccount = (form:userAccountForm):void =>
    {
        const accForm = useForm(form);
        accForm.put(route('settings.update', props.user.user_id), {
            onFinish: () => accountForm.value?.endSubmit(),
        });
    }

    /**
     * User Notification Settings Section
     */
    //  notification form is dynamically created
    interface notificationForm {
        [key: string]: boolean | undefined;
    }

    //  Reorganize settings to provide the initial values for the form
    const objectifyNotifications = () => {
        let initValue:notificationForm = {};
        props.settings.forEach(item => {
            initValue[`type_id_${item.setting_type_id}`] = item.value;
        });

        return initValue;
    }

    const notifForm     = ref<InstanceType<typeof VueForm> | null>(null);
    const notifications = reactive({
        initialValues   : objectifyNotifications(),
    });

    const updateNotifications = (form:notificationForm):void =>
    {
        const notifciationForm = useForm({
            user_id     : props.user.user_id,
            settingsData: form,
        });

        notifciationForm.user_id = props.user.user_id;
        notifciationForm.post(route('settings.store'), {
            onFinish: () => notifForm.value?.endSubmit(),
        });
    }
</script>
