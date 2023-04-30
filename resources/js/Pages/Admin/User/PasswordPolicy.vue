<template>
    <Head title="Password Policy" />
    <div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Update Password Policy</div>
                        <UserSettingsForm :policy="policy" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    import App                 from '@/Layouts/app.vue';
    import UserSettingsForm from '@/Components/Admin/UserSettingsForm.vue';

    type passwordPolicyType = {
        expire            : number,
        min_length        : number,
        contains_uppercase: boolean,
        contains_lowercase: boolean,
        contains_number   : boolean,
        contains_special  : boolean,
        allowOath    : boolean;
        allowRegister: boolean;
        tenantId     : string;
        clientId     : string;
        clientSecret : string;
        redirectUri  : string;
    }

    defineProps<{
        policy: passwordPolicyType;
    }>();
</script>

<script lang="ts">
    export default { layout: App }
</script>
