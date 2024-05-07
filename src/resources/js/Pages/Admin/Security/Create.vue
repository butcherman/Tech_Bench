<template>
    <div>
        <Head title="Upload Certificate" />
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Upload Certificate</div>
                        <CertificateForm :has-key="hasKey" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import CertificateForm from "@/Forms/Admin/Config/CertificateForm.vue";

defineProps<{
    hasKey: boolean;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
