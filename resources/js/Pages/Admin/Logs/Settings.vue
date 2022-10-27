<template>
    <Head title="Log Settings" />
    <App>
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Log Settings</div>
                        <VueForm
                            ref="logSettingsForm"
                            :validation-schema="validationSchema"
                            :initial-values="initialValues"
                            @submit="onSubmit"
                        >
                            <TextInput
                                id="log-days"
                                name="days"
                                label="Days to Keep Logs"
                            />
                            <SelectInput
                                id="log-level"
                                name="level"
                                label="Log Level"
                                :option-list="types"
                            />
                        </VueForm>
                    </div>
                </div>
            </div>
        </div>
    </App>
</template>

<script setup lang="ts">
    import App         from '@/Layouts/app.vue';
    import VueForm     from '@/Components/Base/VueForm.vue';
    import TextInput   from '@/Components/Base/Input/TextInput.vue';
    import SelectInput from '@/Components/Base/Input/SelectInput.vue';
    import { ref }     from 'vue';
    import { useForm } from '@inertiajs/inertia-vue3';

    const props = defineProps<{
        logLevel: string;
        days    : string;
        types   : string[];
    }>();

    const logSettingsForm  = ref<InstanceType<typeof VueForm> | null>(null);
    const initialValues    = {
        days : props.days,
        level: props.logLevel,
    }
    const validationSchema = {}

    interface logFormType {
        days : string;
        level: string;
    }

    const onSubmit = (form:logFormType) => {
        const formData = useForm(form);
        formData.post(route('admin.logs.set-settings'), {
            onFinish: () => logSettingsForm.value?.endSubmit(),
        });
    }
</script>
