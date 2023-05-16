<template>
    <div id="dropzoneDiv" class="dropzone">
    </div>
</template>

<script setup lang="ts">
    import { Dropzone } from 'dropzone';
    import { ref, reactive, onMounted, computed } from 'vue';
    import { usePage } from '@inertiajs/vue3';

    import 'dropzone/dist/dropzone.css';

    const props = defineProps<{
        uploadUrl: string;
    }>();
    const fileData = computed(() => usePage().props.app.fileData);
    let myDrop = null;

    onMounted(() => {
        myDrop = new Dropzone('div#dropzoneDiv', {
            url: 'http://192.168.253.250/customers/files',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': fileData.value.token,
            }
        });

        // console.log(myDrop);
        // console.log(fileData.value.token);

        // myDrop.on('queuecomplete', console.log('queue complete'));

        myDrop.on('addedfile', (file, response) => {
            console.log('added file', file);
        });
        myDrop.on('success', (file, response) => {
            console.log('success file', file);
            console.log('success response', response);
        })
    })
</script>
