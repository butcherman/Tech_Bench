<template>
    <div>
        <Head title="Upload SSL Certificate" />
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Upload SSL Certificate</div>
                        <SslForm />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import SslForm from '@/Forms/Admin/Config/SslForm.vue';
</script>

<script lang="ts">
export default { layout: AppLayout }
</script>
