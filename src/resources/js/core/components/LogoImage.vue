<script setup lang="ts">
import { useAppData } from "../state/appData";

const { logo } = useAppData();
</script>

<template>
    <div><img :src="logo" class="justify-self-center" /></div>
</template>
