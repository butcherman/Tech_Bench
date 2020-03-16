<template>
    <div class="row justify-content-center">
        <div class="col-sm-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-header">Attachements:</div>
                <div class="card-body">
                    <b-list-group>
                        <b-list-group-item v-for="file in tip_files" :key="file.file_id">
                            <a :href="route('download', [file.file_id, file.files.file_name])">{{file.files.file_name}}</a>
                        </b-list-group-item>
                        <b-list-group-item v-show="tip_files.length == 0">
                            <h5 class="text-center">No Attachements</h5>
                        </b-list-group-item>
                    </b-list-group>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: [
        'tip_files'
    ],
}
</script>
