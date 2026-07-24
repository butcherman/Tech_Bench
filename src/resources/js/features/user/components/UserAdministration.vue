<script setup lang="ts">
import DataTable from "@/features/dataTable/DataTable.vue";
import { useUserAdministrationHelper } from "../composables/userAdministrationHelper";

defineProps<{
    userList: User[];
}>();

const { userTableColumns } = useUserAdministrationHelper();
</script>

<template>
    <div>
        <DataTable :columns="userTableColumns" :data="userList" />
    </div>
</template>
