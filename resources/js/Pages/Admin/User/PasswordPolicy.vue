<template>
    <Head title="Password Policy" />
    <div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Update Password Policy</div>
                        <VueForm
                            ref="passwordPolicyForm"
                            :validation-schema="policy.validationSchema"
                            :initial-values="policy.initialValues"
                            @submit="onSubmit"
                        >
                            <TextInput
                                id="password-expires"
                                name="expire"
                                label="Password Expires in Days (enter 0 for no expiration)"
                            />
                            <fieldset>
                                <legend>Password Complexity</legend>
                                <RangeInput
                                    id="min-length"
                                    name="min_length"
                                    label="Password Minimum Length"
                                />
                                <div class="m-3">
                                    <p>A password should contain at least one each of the following:</p>
                                    <CheckboxSwitch
                                        id="policy-uppercase"
                                        name="contains_uppercase"
                                        label="Uppercase Letter"
                                    />
                                    <CheckboxSwitch
                                        id="policy-lowercase"
                                        name="contains_lowercase"
                                        label="Lowercase Letter"
                                    />
                                    <CheckboxSwitch
                                        id="policy-number"
                                        name="contains_number"
                                        label="Number (0-9)"
                                    />
                                    <CheckboxSwitch
                                        id="policy-specital"
                                        name="contains_special"
                                        label="Special Character (!@#$%^&*)"
                                    />
                                </div>
                            </fieldset>
                        </VueForm>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    import App                 from '@/Layouts/app.vue';
    import VueForm             from '@/Components/Base/VueForm.vue';
    import TextInput           from '@/Components/Base/Input/TextInput.vue';
    import RangeInput          from '@/Components/Base/Input/RangeInput.vue';
    import CheckboxSwitch      from '@/Components/Base/Input/CheckboxSwitch.vue';
    import { ref, reactive }   from 'vue';
    import { useForm }         from '@inertiajs/inertia-vue3';

    interface passwordPolicyType {
        expire            : number,
        min_length        : number,
        contains_uppercase: boolean,
        contains_lowercase: boolean,
        contains_number   : boolean,
        contains_special  : boolean,
    }

    const passwordPolicyForm = ref<InstanceType<typeof VueForm> | null>(null);
    const props              = defineProps<{
        policy: passwordPolicyType;
    }>();

    const policy = reactive({
        validationSchema: {},
        initialValues   : props.policy,
    });

    const onSubmit = (form:passwordPolicyType) => {
        console.log(form);

        const formData = useForm(form);
        formData.post(route('admin.users.password-policy.store'), {
            onFinish: () => passwordPolicyForm.value?.endSubmit(),
        });
    }
</script>

<script lang="ts">
    export default { layout: App }
</script>
