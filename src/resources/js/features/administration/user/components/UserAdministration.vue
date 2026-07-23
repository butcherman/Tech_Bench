<script setup lang="ts">
import { useUserAdministration } from "../composables/userAdministration";

const { userTableColumns } = useUserAdministration();

defineProps<{
    userList: User[];
}>();
</script>

<template>
    <div>
        <h3>user list goes here.</h3>
    </div>
</template>
