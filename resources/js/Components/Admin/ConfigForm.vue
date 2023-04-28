<template>
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
            help="This is the URL that will be used to access the Tech Bench"
            @change="updateRedirectUri"
        />
        <SelectOptionGroupInput
            id="timezone"
            name="timezone"
            label="Timezone"
            :option-list="tz_list"
        />
        <RangeInput
            id="file-size"
            name="filesize"
            label="Max Uploaded File Size"
            :min="5000"
            :max="10737418240"
            format="prettybytes"
        />
    </VueForm>
</template>

<script setup lang="ts">
    import VueForm                from '@/Components/Base/VueForm.vue';
    import TextInput              from '@/Components/Base/Input/TextInput.vue';
    import SelectOptionGroupInput from '@/Components/Base/Input/SelectOptionGroup.vue';
    import CheckboxSwitch         from '@/Components/Base/Input/CheckboxSwitch.vue';
    import RangeInput             from '@/Components/Base/Input/RangeInput.vue';
    import { ref }                from 'vue';
    import { useForm }            from '@inertiajs/vue3';
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
