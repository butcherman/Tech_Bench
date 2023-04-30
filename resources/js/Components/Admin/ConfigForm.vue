<template>
    <VueForm
        ref="configForm"
        :validation-schema="validationSchema"
        :initial-values="initialValues"
        submit-text="Update Basic Settings"
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
    import RangeInput             from '@/Components/Base/Input/RangeInput.vue';
    import SelectOptionGroupInput from '@/Components/Base/Input/SelectOptionGroup.vue';
    import { ref }                from 'vue';
    import { useForm }            from '@inertiajs/vue3';
    import { object, string, number }               from 'yup';

    type settingsType = {
        url          : string;
        timezone     : string;
        filesize     : number;
    }

    type timezoneType = {
        [key:string]: {
            [key:string]:string
        }
    }

    const props = defineProps<{
        settings: settingsType;
        tz_list : timezoneType;
    }>();

    const emit = defineEmits(['step-completed']);

    const configForm       = ref<InstanceType<typeof VueForm> | null>(null);
    const initialValues    = props.settings;
    const validationSchema = object().shape({
        url          : string().url().required('You must have a Fully Qualified Domain Name for this application to work properly'),
        timezone     : string().required(),
        filesize     : number().required(),
    });

    const onSubmit = (form:settingsType) => {
        const formData = useForm(form);
        formData.post(route('admin.set-config'), {
            onFinish: () => {
                configForm.value?.endSubmit()
                emit('step-completed');
            },
        });
    }

    const updateRedirectUri = (newUri:string) => {
        configForm.value?.setFieldValue('redirectUri', `${newUri}/auth/callback`);
    }
</script>
