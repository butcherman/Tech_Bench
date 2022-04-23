<template>
    <div>
        <div class="row">
            <div class="col-sm-10 grid-margin">
                <h3>
                    <i :class="bookmark_class" :title="bookmark_title" v-b-tooltip.hover @click="toggleFav"></i>
                    {{tip.subject}}
                </h3>
                <div class="tip-details">
                    <span class="d-block d-sm-inline-block"><strong>ID: </strong>{{tip.tip_id}}</span>
                    <span class="d-block d-sm-inline-block"><strong>Created: </strong>{{tip.created_at}}</span>
                    <span class="d-block d-sm-inline-block"><strong>Last Updated: </strong>{{tip.updated_at}}</span>
                </div>
            </div>
            <div class="col-sm-2">
                <b-button :href="route('tips.download', tip.tip_id)" variant="info" size="sm" block pill title="Download as PDF" v-b-tooltip.hover>
                    <i class="fas fa-download"></i>
                    Download Tip
                </b-button>
                <manage-tip :tip_id="tip.tip_id" :permissions="user_data.permissions"></manage-tip>
            </div>
        </div>
        <div class="row mt-2 mt-md-0">
            <div class="col tip-equipment grid-margin">
                <div><strong>For Equipment:</strong></div>
                <b-badge pill variant="info" class="ml-1 mb-1" v-for="equip in tip.equipment_type" :key="equip.equip_id">{{equip.name}}</b-badge>
            </div>
        </div>
        <div class="row grid-margin justify-content-center">
            <div class="col">
                <div class="card rounded">
                    <div class="card-body">
                        <div class="card-title">Details:</div>
                        <div v-html="tip.details" class="tip-body"></div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="tip.file_uploads.length >= 1" class="row grid-margin justify-content-center">
            <div class="col">
                <div class="card rounded">
                    <div class="card-body">
                        <div class="card-title">Attachments:</div>
                        <ul class="list-group px-5">
                            <li v-for="file in tip.file_uploads" :key="file.file_id" class="list-group-item">
                                <a :href="route('download', [file.file_id, file.file_name])">{{file.file_name}}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <discussion :comments="tip.tech_tip_comment" :tip_id="tip.tip_id" :permissions="user_data.permissions.comment"></discussion>
    </div>
</template>

<script>
    import App        from '../../Layouts/app';
    import Discussion from '../../Components/TechTips/discussion.vue';
    import manageTip  from '../../Components/TechTips/manageTip.vue';

    export default {
        components: { manageTip, Discussion },
        layout: App,
        props: {
            /**
             * object from /app/models/techtip
             */
            tip: {
                type:     Object,
                required: true,
            },
            /**
             * object from /app/models/user
             */
            user_data: {
                type:     Object,
                required: true,
            }
        },
        data() {
            return {
                is_fav: this.user_data.fav,
            }
        },
        computed: {
            bookmark_class()
            {
                return this.is_fav ? 'fas fa-bookmark bookmark-checked' : 'far fa-bookmark bookmark-unchecked';
            },
            bookmark_title()
            {
                return this.is_fav ? 'Remove From Bookmarks' : 'Add to Bookmarks'
            },
        },
        methods: {
            /**
             * Ajax call to add or remove tip_id from user_tech_tip_bookmarks table
             */
            toggleFav()
            {
                var form = {
                    tip_id: this.tip.tip_id,
                    state:  !this.is_fav,
                }

                axios.post(route('tips.bookmark'), form)
                    .then(() => {
                        this.is_fav = !this.is_fav;
                    }).catch(error => this.eventHub.$emit('axiosError', error));
            }
        },
        metaInfo: {
            title: 'Tech Tip Details',
        }
    }
</script>
