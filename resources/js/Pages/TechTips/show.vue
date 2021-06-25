<template>
    <div>
        <div class="row grid-margin">
            <div class="col-sm-10">
                <h3>
                    <i :class="bookmark_class" :title="bookmark_title" v-b-tooltip.hover @click="toggleFav"></i>
                    {{details.subject}}
                </h3>
                <div class="tip-details">
                    <span class="d-block d-sm-inline-block"><strong>ID: </strong>{{details.tip_id}}</span>
                    <span class="d-block d-sm-inline-block"><strong>Created: </strong>{{details.created_at}}</span>
                    <span class="d-block d-sm-inline-block"><strong>Last Updated: </strong>{{details.updated_at}}</span>
                </div>
            </div>
            <div class="col-sm-2">
                <b-button :href="route('tips.download', details.tip_id)" variant="info" size="sm" block pill title="Download as PDF" v-b-tooltip.hover>
                    <i class="fas fa-download"></i>
                    Download Tip
                </b-button>
            </div>
        </div>
        <div class="row grid-margin">
            <div class="col tip-equipment">
                <div><strong>For Equipment:</strong></div>
                <b-badge pill variant="info" class="ml-1 mb-1" v-for="equip in details.equipment_type" :key="equip.equip_id">{{equip.name}}</b-badge>
            </div>
        </div>
        <div class="row grid-margin justify-content-center">
            <div class="col">
                <div class="card rounded">
                    <div class="card-body">
                        <div class="card-title">Details:</div>
                        <div v-html="details.details"></div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="details.file_uploads.length >= 1" class="row grid-margin justify-content-center">
            <div class="col">
                <div class="card rounded">
                    <div class="card-body">
                        <div class="card-title">Attachments:</div>
                        <ul class="list-group px-5">
                            <li v-for="file in details.file_uploads" :key="file.file_id" class="list-group-item">
                                <a :href="route('download', [file.file_id, file.file_name])">{{file.file_name}}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import App from '../../Layouts/app';

    export default {
        layout: App,
        props: {
            details: {
                type:     Object,
                required: true,
            },
            fav: {
                type:     Boolean,
                required: true,
            }
        },
        data() {
            return {
                is_fav: this.fav,
            }
        },
        created() {
            //
        },
        mounted() {
             //
        },
        computed: {
            bookmark_class()
            {
                return this.is_fav ? 'fas fa-bookmark bookmark-checked' : 'far fa-bookmark bookmark-unchecked';
            },
            bookmark_title()
            {
                return this.is_fav ? 'Remove From Bookmarks' : 'Add to Bookmarks'
            }
        },
        watch: {
             //
        },
        methods: {
            toggleFav()
            {
                axios.put(this.route('tips.bookmark'), {tip_id: this.details.tip_id, state: !this.is_fav})
                    .then(this.is_fav = !this.is_fav);
            },
        }
    }
</script>
