<template>
    <div>
        <Head title="Update Data Type" />
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <DataTypeForm :data-type="dataType" />
                    </div>
                </div>
            </div>
        </div>
        <DataTypeHelp />
    </div>
</template>

<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import DataTypeForm from '@/Forms/Equipment/DataTypeForm.vue';
import DataTypeHelp from '@/Components/Equipment/DataTypeHelp.vue';

defineProps<{
    dataType: dataTypes;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout }
</script>
