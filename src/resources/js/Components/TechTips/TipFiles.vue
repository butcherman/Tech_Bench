<template>
    <div v-if="tipFiles.length" class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Attachments:</div>
                    <ul class="list-group">
                        <li
                            v-for="file in tipFiles"
                            :key="file.file_id"
                            class="list-group-item"
                        >
                            <a
                                :href="
                                    $route('download', [
                                        file.file_id,
                                        file.file_name,
                                    ])
                                "
                            >
                                {{ file.file_name }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
defineProps<{
    tipFiles: fileUpload[];
}>();
</script>
