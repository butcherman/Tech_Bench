<template>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Customer Summary Report</div>
                    <CustomerReportForm
                        :submit-route="
                            $route('reports.run', [
                                'customers',
                                'customer-summary-report',
                            ])
                        "
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import CustomerReportForm from "@/Forms/Report/Customer/CustomerReportForm.vue";
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
