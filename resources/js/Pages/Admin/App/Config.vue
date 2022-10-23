<template>
    <Head title="Application Settings" />
    <App>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Configuration</div>
                        <VueForm
                            ref="configForm"
                            :validation-schema="validationSchema"
                            :initial-values="initialValues"
                            @submit="onSubmit"
                        >
                            <TextInput
                                id="url"
                                name="url"
                                label="Site URL"
                                @change="updateRedirectUri"
                            />
                            <SelectOptionGroupInput
                                id="timezone"
                                name="timezone"
                                label="Timezone"
                                :option-list="tz_list"
                            />
                            <div class="row justify-content-center">
                                <div class="col-md-7">
                                    <CheckboxSwitch
                                        id="oath-login"
                                        name="allowOath"
                                        class="d-inline-block"
                                        label="Allow Office 365 Login"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#oath-form-data"
                                    >
                                    </CheckboxSwitch>
                                    &nbsp;
                                    <span
                                        title="What is this?"
                                        class="pointer pl-2 text-primary"
                                        @click.prevent="showHelp('allowOath')"
                                        v-tooltip
                                    >
                                        <fa-icon icon="fa-circle-question" />
                                    </span>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div
                                    id="oath-form-data"
                                    class="col-md-11 border collapse p-2 m-0"
                                    :class="{ show : settings.allowOath }"
                                >
                                    <div class="row justify-content-center">
                                        <div class="col-md-10">
                                            <CheckboxSwitch
                                                id="oath-register"
                                                name="allowRegister"
                                                class="d-inline-block"
                                                label="Allow anyone in my organization to login"
                                            />
                                            &nbsp;
                                            <span
                                                title="What is this?"
                                                class="pointer pl-2 text-primary"
                                                @click.prevent="showHelp('allowRegister')"
                                                v-tooltip
                                            >
                                                <fa-icon icon="fa-circle-question" />
                                            </span>
                                        </div>
                                    </div>
                                    <TextInput
                                        id="azure-tenant-id"
                                        name="tenantId"
                                        label="Azure Tenant ID"
                                    />
                                    <TextInput
                                        id="azure-client-id"
                                        name="clientId"
                                        label="Azure Client ID"
                                    />
                                    <TextInput
                                        id="azure-client-secret"
                                        type="password"
                                        name="clientSecret"
                                        label="Azure Client Secret"
                                    />
                                    <TextInput
                                        id="azure-redirect"
                                        type="url"
                                        name="redirectUri"
                                        label="Azure Redirect URI"
                                        disabled
                                    />
                                </div>
                            </div>
                            <RangeInput
                                id="file-size"
                                name="filesize"
                                label="Max Uploaded File Size"
                                :min="5000"
                                :max="10737418240"
                                format="prettybytes"
                            />
                        </VueForm>
                    </div>
                </div>
            </div>
        </div>
    </App>
</template>

<script setup lang="ts">
    import App                    from '@/Layouts/app.vue';
    import VueForm                from '@/Components/Base/VueForm.vue';
    import TextInput              from '@/Components/Base/Input/TextInput.vue';
    import SelectOptionGroupInput from '@/Components/Base/Input/SelectOptionGroup.vue';
    import CheckboxSwitch         from '@/Components/Base/Input/CheckboxSwitch.vue';
    import RangeInput             from '@/Components/Base/Input/RangeInput.vue';
    import { ref }                from 'vue';
    import { useForm }            from '@inertiajs/inertia-vue3';
    import { helpModal }          from '@/Modules/helpModal.module';
    import * as yup               from 'yup';

    interface settingsType {
        url          : string;
        timezone     : string;
        filesize     : number;
        allowOath    : boolean;
        allowRegister: boolean;
        tenantId     : string;
        clientId     : string;
        clientSecret : string;
        redirectUri  : string;
    }

    interface timezoneType {
        [key:string]: {
            [key:string]:string
        }
    }

    const props = defineProps<{
        settings: settingsType;
        tz_list : timezoneType;
    }>();

    const configForm       = ref<InstanceType<typeof VueForm> | null>(null);
    const initialValues    = props.settings;
    const validationSchema = yup.object().shape({
        url          : yup.string().url().required('You must have a Fully Qualified Domain Name for this application to work properly'),
        timezone     : yup.string().required(),
        filesize     : yup.number().required(),
        allowOath    : yup.boolean().required(),
        allowRegister: yup.boolean().when('allowOath', {
            is  : true,
            then: yup.boolean().required(),
        }),
        tenantId     : yup.string().when('allowOath', {
            is  : true,
            then: yup.string().required('Please enter the Azure Tenant ID'),
        }),
        clientId     : yup.string().when('allowOath', {
            is  : true,
            then: yup.string().required('Please enter the Azure Client ID'),
        }),
        clientSecret : yup.string().when('allowOath', {
            is  : true,
            then: yup.string().required('Please enter the Azure Client Secret'),
        }),
        redirectUri  : yup.string().required(),
    });

    const onSubmit = (form:settingsType) => {
        const formData = useForm(form);
        formData.post(route('admin.set-config'), {
            onFinish: () => configForm.value?.endSubmit(),
        });
    }

    const updateRedirectUri = (newUri:string) => {
        configForm.value?.setFieldValue('redirectUri', `${newUri}/auth/callback`);
    }

    const showHelp = (type:string) => {
        helpModal(getHelpMsg(type), {
            title: 'What is this?',
        });
    }

    const getHelpMsg = (type:string):string => {
        let msg = '';

        switch(type)
        {
            case 'allowOath':
                msg = 'Allow users to use their Microsoft Office 365 credentials to log into the Tech Bench';
                break;
            case 'allowRegister':
                msg = 'When set, any user in your orginazition can log in using their Microsoft Office 365 '+
                      'Credentials.  With this option turned off, only users you manually create can use their '+
                      'Microsoft Office 365 Credentials to login';
        }

        return msg;
    }
</script>
