<template>
    <div>
        <Head title="Create Data Type" />
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <DataTypeForm />
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center my-4">
            <div class="col-md-8">
                <DataTypeHelp />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import DataTypeForm from "@/Forms/Equipment/DataTypeForm.vue";
import DataTypeHelp from "@/Components/Equipment/DataTypeHelp.vue";
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
