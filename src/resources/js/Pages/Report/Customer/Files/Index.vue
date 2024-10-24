<template>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Customer Files Report</div>
                    <CustomerFilesReportForm :file-types="fileTypes" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import CustomerFilesReportForm from "@/Forms/Report/Customer/CustomerFilesReportForm.vue";

defineProps<{
    fileTypes: customerFileType[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
