<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import DataTypeForm from "@/Forms/Equipment/DataTypeForm.vue";
import DataTypeHelp from "@/Components/Equipment/DataTypeHelp.vue";

defineProps<{
    dataFieldType: dataTypes;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div>
        <div class="flex justify-center">
            <Card class="tb-card">
                <DataTypeForm :data-field-type="dataFieldType" />
            </Card>
        </div>
        <div class="flex justify-center">
            <DataTypeHelp class="tb-card" />
        </div>
    </div>
</template>
