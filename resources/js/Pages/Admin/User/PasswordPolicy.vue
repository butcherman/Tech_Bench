<template>
    <Head title="Password Policy" />
    <div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Password Policy</div>
                        <PasswordPolicyForm :policy="policy" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import PasswordPolicyForm from '@/Forms/Admin/User/PasswordPolicyForm.vue';

defineProps<{
    policy: passwordPolicy;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout }
</script>
