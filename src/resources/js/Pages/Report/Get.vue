<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import ReportLoader from "@/Components/Report/ReportLoader.vue";
import { defineAsyncComponent } from "vue";

const props = defineProps<{
    group: string;
    form: string;
    props: any | any[];
}>();

const FormComponent = defineAsyncComponent({
    loader: () => import(`../../Forms/Report/${props.group}/${props.form}.vue`),
    loadingComponent: ReportLoader,
    delay: 200,
});
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="flex justify-center">
        <Card class="tb-card">
            <FormComponent v-bind="props.props" />
        </Card>
    </div>
</template>
