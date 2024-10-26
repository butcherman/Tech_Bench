<template>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">New File Link</div>
                    <FileLinkForm :default-expire="defaultExpire" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import FileLinkForm from "@/Forms/FileLink/FileLinkForm.vue";

defineProps<{
    defaultExpire: string;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
