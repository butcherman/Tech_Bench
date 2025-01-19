<template>
    <div>
        <Card class="tb-card text-center">Comming Soon...</Card>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import { ref, reactive, onMounted } from "vue";
// TODO - Add Page
const props = defineProps<{}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
