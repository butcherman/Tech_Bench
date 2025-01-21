<template>
    <div class="flex flex-col gap-3 place-items-center">
        <Card class="tb-card" title="Create Data Type">
            <DataTypeForm />
        </Card>
        <div class="tb-card">
            <DataTypeHelp />
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import DataTypeForm from "@/Forms/Equipment/DataTypeForm.vue";
import DataTypeHelp from "@/Components/Equipment/DataTypeHelp.vue";
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
