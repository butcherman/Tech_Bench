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
        <div class="row grid-margin justify-content-center">
            <div class="col-md-8">
                <div class="card rounded">
                    <div class="card-body">
                        <div class="card-title">Discussion:</div>
                        <div v-if="details.tech_tip_comment.length" class="mb-3">
                            <div v-for="comment in details.tech_tip_comment" :key="comment.comment_id" class="border rounded p-4 mt-2">
                                <div class="mb-2">
                                    <span class="float-right">
                                        <i class="fas fa-flag pl-2" :class="isFlaggedClass(comment.flagged)" title="Flag as Innappropriate" v-b-tooltip.hover @click="flagComment(comment)"></i>
                                        <!-- <i v-if="canEditComment(comment.user)" class="fas fa-pencil-alt text-info pointer" title="Edit" v-b-tooltip.hover></i> -->
                                        <i v-if="canEditComment(comment.user)" class="far fa-trash-alt text-danger pointer" title="Delete" v-b-tooltip.hover @click="deleteComment(comment)"></i>
                                    </span>
                                    {{comment.comment}}
                                </div>
                                <div class="border-top text-secondary">
                                    {{comment.user.full_name}}
                                    <div class="float-right">{{comment.created_at}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3" v-else>
                            <h5 class="text-center">No Comments Yet</h5>
                        </div>
                        <b-overlay :show="submitted">
                            <template #overlay>
                                <atom-loader></atom-loader>
                            </template>
                            <ValidationObserver v-slot="{handleSubmit}" v-if="permissions.comment.create">
                                <b-form @submit.prevent="handleSubmit(submitComment)" novalidate>
                                    <ValidationProvider v-slot="v" rules="required">
                                        <b-form-group>
                                            <b-form-textarea
                                                v-model="form.comment"
                                                placeholder="Comment on this Tech Tip..."
                                                rows="3"
                                                max-rows="6"
                                            ></b-form-textarea>
                                            <b-form-invalid-feedback :state="false">{{v.errors[0]}}</b-form-invalid-feedback>
                                        </b-form-group>
                                    </ValidationProvider>
                                    <submit-button class="mt-2" button_text="Add Comment" :submitted="submitted"></submit-button>
                                </b-form>
                            </ValidationObserver>
                        </b-overlay>
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
            },
            permissions: {
                type:     Object,
                required: true,
            }
        },
        data() {
            return {
                is_fav: this.fav,
                submitted: false,
                form: {
                    tip_id:  this.details.tip_id,
                    comment: null,
                }
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
            submitComment()
            {
                this.submitted = true;
                this.$inertia.post(route('tips.comments.store'), this.form, {
                    onFinish: () => {
                        this.submitted    = false;
                        this.form.comment = null;
                    }
                });
            },
            flagComment(comment)
            {
                console.log(comment);

                if(!comment.flagged)
                {
                    this.$inertia.get(route('tips.comments.flag', comment.id));
                }
            },
            isFlaggedClass(isFlagged)
            {
                return isFlagged ?  'text-danger' : 'pointer text-muted';
            },
            canEditComment(user)
            {
                if(this.permissions.comment.manage)
                {
                    return true;
                }

                return user.username === this.$page.props.user.username;
            },
            deleteComment(comment)
            {
                this.$bvModal.msgBoxConfirm('Please confirm you want to delete this comment.', {
                    title: 'Are You Sure?',
                    size: 'md',
                    okVariant: 'danger',
                    okTitle: 'Yes',
                    cancelTitle: 'No',
                    centered: true,
                }).then(res => {
                    if(res)
                    {
                        this.$inertia.delete(route('tips.comments.destroy', comment.id));
                    }
                });
            }
        }
    }
</script>
