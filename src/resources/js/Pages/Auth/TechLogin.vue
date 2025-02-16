<template>
    <div class="tb-card">
        <h4 class="text-center">Login Page</h4>
    </div>
</template>

<script setup lang="ts">
import AuthLayout from "@/Layouts/Auth/AuthLayout.vue";
// import Card from "@/Components/_Base/Card.vue";
import { ref, reactive, onMounted } from "vue";

// TODO - Add Page.
const props = defineProps<{}>();
</script>

<script lang="ts">
export default { layout: AuthLayout };
</script>
