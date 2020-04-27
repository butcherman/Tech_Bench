<template>
    <div>
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="row">
                    <div class="col-md-10">
                        <h3>
                            <bookmark :is_fav="is_fav" toggle_route="tip.toggle-fav" :bookmark_id="tip_data.details.tip_id"></bookmark>
                            {{tip_data.details.subject}}
                        </h3>
                        <div class="tip-details">
                            <span class="d-block d-sm-inline-block"><strong>ID:</strong>  {{tip_data.details.tip_id}}</span>
                            <span class="d-block d-sm-inline-block"><strong>Created:</strong> {{tip_data.details.created_at}}</span>
                            <span class="d-block d-sm-inline-block"><strong>Updated:</strong> {{tip_data.details.updated_at}}</span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <b-button variant="info" block pill size="sm" :href="route('tip.downloadTip', tip_data.details.tip_id)">
                            <i class="fas fa-download"></i>
                            Download Tip
                        </b-button>
                        <b-button v-if="can_edit" variant="warning" block pill size="sm" :href="route('tips.edit', tip_data.details.tip_id)">
                            <i class="far fa-edit"></i>
                            Edit Tip
                        </b-button>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <div class="tip-equipment">
                            <div><strong>For Equipment:</strong></div>
                            <b-badge pill variant="info" v-for="sys in tip_data.details.system_types" :key="sys.sys_id" class="ml-1 mb-1">{{sys.name}}</b-badge>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 stretch-card grid-margin">
                <div class="card rounded">
                    <div class="card-body tip-description">
                        <div class="card-title border-bottom">Details:</div>
                        <div v-html="tip_data.details.description"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" v-if="tip_data.files.length">
            <div class="col-12 stretch-card grid-margin">
                <div class="card rounded">
                    <div class="card-body">
                        <div class="card-title border-bottom">Attachments:</div>
                        <ul class="pl-5">
                            <li v-for="file in tip_data.files" :key="file.tip_file_id">
                                <a :href="route('download', [file.file_id, file.files.file_name])">{{file.files.file_name}}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 stretch-card grid-margin">
                <div class="card rounded">
                    <div class="card-body">
                        <div class="card-title border-bottom">Discussion:</div>
                        <tech-tip-comments :tip_id="tip_data.details.tip_id"></tech-tip-comments>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            tip_data: {
                type: Object,
                required: true,
            },
            is_fav: {
                type: Boolean,
                required: false,
                default: false,
            },
            can_edit: {
                type: Boolean,
                required: false,
                default: false,
            }
        },
    }
</script>
