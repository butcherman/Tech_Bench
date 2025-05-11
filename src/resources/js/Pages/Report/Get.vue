<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import AtomLoader from "@/Components/_Base/Loaders/AtomLoader.vue";
import Card from "@/Components/_Base/Card.vue";
import { defineAsyncComponent } from "vue";

const props = defineProps<{
    group: string;
    form: string;
    props: any[];
}>();

const FormComponent = defineAsyncComponent({
    loader: () => import(`../../Forms/Report/${props.group}/${props.form}.vue`),
    loadingComponent: AtomLoader,
});
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="flex justify-center">
        <Card class="tb-card">
            <FormComponent :customer-list="[]" />
        </Card>
    </div>
</template>
