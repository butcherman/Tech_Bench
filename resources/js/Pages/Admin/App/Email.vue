<template>
    <Head title="Email Settings" />
    <App>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Email Settings</div>
                        <VueForm
                            ref="emailForm"
                            :validation-schema="validationSchema"
                            :initial-values="initialValues"
                            @submit="onSubmit"
                        >
                            <TextInput
                                id="from-address"
                                type="email"
                                name="from_address"
                                label="From Email Address"
                            />
                            <TextInput
                                id="host-address"
                                name="host"
                                label="SMTP Host"
                            />
                            <TextInput
                                id="host-port"
                                type="number"
                                name="port"
                                label="SMTP Port"
                            />
                            <SelectInput
                                id="encryption-type"
                                name="encryption"
                                label="Encryption Method"
                                :option-list="encryptionTypes"
                            />
                            <div class="row justify-content-center">
                                <div class="col-md-7">
                                    <CheckboxSwitch
                                        id="require-auth"
                                        name="requireAuth"
                                        label="Require Authentication"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#auth-data"
                                    >
                                    </CheckboxSwitch>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div
                                    id="auth-data"
                                    class="col-md-11 border collapse p-2 m-0"
                                    :class="{ show : settings.username }"
                                >
                                    <TextInput
                                        id="auth-username"
                                        name="username"
                                        label="Username"
                                    />
                                    <TextInput
                                        id="auth-password"
                                        type="password"
                                        name="password"
                                        label="Password"
                                    />
                                </div>
                            </div>
                        </VueForm>
                        <div class="text-center">
                            <Link
                                as="button"
                                :href="route('admin.test-email')"
                                type="button"
                                class="btn btn-warning mt-2 w-100"
                                :disabled="testEmailDisable"
                                @click="testEmailDisable = true"
                                @finish="testEmailDisable = false"
                            >
                                <span v-if="testEmailDisable" class="spinner-border spinner-border-sm" />
                                Send Test Email
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </App>
</template>

<script setup lang="ts">
    import App            from '@/Layouts/app.vue';
    import VueForm        from '@/Components/Base/VueForm.vue';
    import TextInput      from '@/Components/Base/Input/TextInput.vue';
    import CheckboxSwitch from '@/Components/Base/Input/CheckboxSwitch.vue';
    import SelectInput    from '@/Components/Base/Input/SelectInput.vue';
    import { ref }        from 'vue';
    import { useForm }    from '@inertiajs/inertia-vue3';
    import { toLower }    from 'lodash';
    import * as yup       from 'yup';

    interface emSettingsType {
        host        : string;
        port        : number;
        encryption  : string;
        username    : string;
        password    : string;
        from_address: string;
        requireAuth : boolean;
    }

    const props = defineProps<{
        settings:emSettingsType;
    }>();

    const testEmailDisable = ref(false);
    const emailForm        = ref<InstanceType<typeof VueForm> | null>(null);
    const initialValues    = props.settings;
    const validationSchema = yup.object().shape({
        from_address: yup.string().email().required('You must have a From Email Address'),
        host        : yup.string().required('What Host should emails be relayed through?'),
        port        : yup.number().required('What port is used to send emails?'),
        encryption  : yup.string().required(),
        username    : yup.string().when('requireAuth', {
            is  : true,
            then: yup.string().required('Username field is required'),
        }),
        password    : yup.string().when('requireAuth', {
            is  : true,
            then: yup.string().required('Password field is required'),
        })
    });
    const encryptionTypes  = [ 'NONE', 'TLS', 'SSL' ];

    const onSubmit = (form:emSettingsType) => {
        //  If Authentication has been disabled, clear out username and password fields
        if(!form.requireAuth)
        {
            form.username = '';
            form.password = '';
        }

        //  Convert encryption string to lower
        form.encryption = toLower(form.encryption);

        const formData = useForm(form);
        formData.post(route('admin.set-email'), {
            onFinish: () => emailForm.value?.endSubmit(),
        });
    }
</script>
