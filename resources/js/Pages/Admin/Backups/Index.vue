<template>
    <App>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Backup Options</div>
                        <VueForm
                            ref="backupForm"
                            :validation-schema="validationSchema"
                            :initial-values="initialValues"
                            submit-text="Save"
                            @submit="onSubmit"
                        >
                            <CheckboxSwitch
                                id="enable"
                                name="enable"
                                label="Enable Nightly Backups"
                            />
                            <TextInput
                                id="password"
                                name="password"
                                type="password"
                                label="Encryption Password (leave blank to disable)"
                            />
                            <TextInput
                                id="email"
                                name="email"
                                type="email"
                                label="Email Notifications To"
                            />
                        </VueForm>
                        <div class="text-center mt-4">
                            <h6>Backup Storage Rules:</h6>
                            <ul class="list-group">
                                <li class="list-group-item">
                                    Daily backups are kept for {{ rules.daily }} days
                                </li>
                                <li class="list-group-item">
                                    Weekly backups are kept for {{ rules.weekly }} weeks
                                </li>
                                <li class="list-group-item">
                                    Monthly backups are kept for {{ rules.monthly }} months
                                </li>
                                <li class="list-group-item">
                                    Yearly backups are kept for {{ rules.yearly }} years
                                </li>
                            </ul>
                        </div>
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
    import CheckboxSwitch from '@/Components/Base/Input/CheckboxSwitch.vue';
    import { ref, reactive, onMounted } from 'vue';
    import { useForm } from '@inertiajs/inertia-vue3';
    import * as yup from 'yup';

    interface backupSettingsType {
        enable  : boolean;
        password: string | null;
        email   : string | null;
    }

    const props = defineProps<{
        rules: {
            daily  : string;
            weekly : string;
            monthly: string;
            yearly : string;
        }
        settings: backupSettingsType;
    }>();

    const backupForm       = ref<InstanceType<typeof VueForm> | null>(null);
    const validationSchema = {
        enable  : yup.boolean().required(),
        password: yup.string().nullable(),
        email   : yup.string().email().nullable(),
    }
    const initialValues = props.settings;

    const onSubmit = (form:backupSettingsType) => {
        const formData = useForm(form);
        formData.post(route('admin.backups.store'), {
            onFinish: () => backupForm.value?.endSubmit(),
        });

    }
</script>
