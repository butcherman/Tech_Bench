<template>
    <VueForm
        ref="emailForm"
        :validation-schema="validationSchema"
        :initial-values="initialValues"
        submit-text="Save"
        @submit="onSubmit"
    >
        <TextInput
            id="from-address"
            type="email"
            name="from_address"
            label="From Email Address"
            placeholder="no-reply@your-domain.com"
            help="The From Email Address that will show when an email is sent"
        />
        <TextInput
            id="host-address"
            name="host"
            label="SMTP Host"
            placeholder="smtp.your-email-server.com"
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
                    placeholder="Username"
                />
                <TextInput
                    id="auth-password"
                    type="password"
                    name="password"
                    label="Password"
                    placeholder="Password"
                />
            </div>
        </div>
    </VueForm>
    <div class="text-center">
        <Link
            as="button"
            :href="$route('admin.test-email')"
            type="button"
            class="btn btn-warning mt-2 w-100"
            :disabled="testEmailDisable"
            @click="testEmailDisable = true"
            @finish="testEmailDisable = false"
        >
            <span
                v-if="testEmailDisable"
                class="spinner-border spinner-border-sm"
            />
            Send Test Email
        </Link>
        <p>
            <strong>Note:</strong>
            You must save the email settings before sending
            a test email
        </p>
    </div>
</template>

<script setup lang="ts">
    import { ref, reactive, onMounted } from 'vue';
    import VueForm from '@/Components/Base/VueForm.vue';
    import TextInput from '../Base/Input/TextInput.vue';
    import SelectInput from '../Base/Input/SelectInput.vue';
    import CheckboxSwitch from '../Base/Input/CheckboxSwitch.vue';
    import { object, string, number, boolean } from 'yup';
    import { useForm } from '@inertiajs/vue3';
    import { _ } from 'lodash';

    type emSettingsType = {
        host        : string;
        port        : number;
        encryption  : string;
        username    : string;
        password    : string;
        from_address: string;
        requireAuth : boolean;
    }

    const $route = route;
    const emailForm = ref<InstanceType<typeof VueForm> | null>(null);
    const props = defineProps<{
        settings:emSettingsType;
    }>();

    const testEmailDisable = ref(false);
    const encryptionTypes  = [ 'NONE', 'TLS', 'SSL' ];
    const initialValues = props.settings;
    const validationSchema = object({
        from_address: string().email().required('You must have a From Email Address'),
        host        : string().required('What Host should emails be relayed through?'),
        port        : number().required('What port is used to send emails?'),
        encryption  : string().required(),
        requireAuth: boolean().required(),
        username: string().when('requireAuth', {
            is: true,
            then: (schema) => schema.required(),
            otherwise: (schema) => schema.nullable(),
        }),
        password: string().when('requireAuth', {
            is: true,
            then: (schema) => schema.required('Username field is required'),
            otherwise: (schema) => schema.nullable('Password field is required'),
        })
    });
    const onSubmit = (form:emSettingsType) => {
        console.log('submitted');
        //  If Authentication has been disabled, clear out username and password fields
        if(!form.requireAuth)
        {
            form.username = '';
            form.password = '';
        }

        //  Convert encryption string to lower
        form.encryption = _.toLower(form.encryption);

        const formData = useForm(form);
        formData.post(route('admin.set-email'), {
            onFinish: () => emailForm.value?.endSubmit(),
        });
    }
</script>
