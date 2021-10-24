<template>
    <div class="row justify-content-center">
        <div class="col-md-8 grid-margin">
            <div class="card rounded">
                <div class="card-body">
                    <div class="card-title">
                        Discussion:
                    </div>
                    <div v-if="commentList.length == 0">
                        <h5 class="text-center">No Comments Yet</h5>
                    </div>
                    <div v-else class="mb-4">
                        <div v-for="comment in commentList" :key="comment.comment_id" class="border rounded p-4 mt-2">
                            <div class="mb-2">
                                <span class="float-right">
                                    <i class="fa-flag pl-2"
                                        :class="getFlaggedClass(comment)"
                                        title="Flag as Innappropriate"
                                        v-b-tooltip.hover
                                        @click="flagComment(comment)"
                                    ></i>
                                    <i class="fas fa-pencil-alt pointer pl-2 text-muted"
                                        title="Edit Comment"
                                        v-b-tooltip.hover
                                        v-if="canEdit(comment)"
                                        @click="editComment(comment)"
                                    ></i>
                                    <i class="far fa-trash-alt text-danger pointer pl-2"
                                        title="Delete"
                                        v-b-tooltip.hover
                                        v-if="canDelete(comment)"
                                        @click="deleteComment(comment)"
                                    ></i>
                                </span>
                                {{comment.comment}}
                            </div>
                            <div class="border-top text-secondary">
                                {{comment.user.full_name}}
                                <div class="float-right">{{comment.created_at}}</div>
                            </div>
                        </div>
                    </div>
                    <b-overlay :show="submitted">
                        <template #overlay>
                            <atom-loader></atom-loader>
                        </template>
                        <ValidationObserver v-slot="{handleSubmit}" ref="validator">
                            <b-form @submit.prevent="handleSubmit(submitComment)" novalidate v-if="permissions.create">
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
        <b-modal
            id="edit-comment-modal"
            ref="edit-comment-modal"
            title="Edit Comment"
            hide-footer
        >
            <b-overlay :show="loading">
                <template #overlay>
                    <form-loader></form-loader>
                </template>
                <ValidationObserver v-slot="{handleSubmit}" ref="validator">
                    <b-form @submit.prevent="handleSubmit(updateComment)" novalidate>
                        <ValidationProvider v-slot="v" rules="required">
                            <b-form-group>
                                <b-form-textarea
                                    v-model="updateForm.comment"
                                    rows="3"
                                    max-rows="6"
                                ></b-form-textarea>
                                <b-form-invalid-feedback :state="false">{{v.errors[0]}}</b-form-invalid-feedback>
                            </b-form-group>
                        </ValidationProvider>
                        <submit-button class="mt-2" button_text="Update Comment" :submitted="loading"></submit-button>
                    </b-form>
                </ValidationObserver>
            </b-overlay>
        </b-modal>
    </div>
</template>

<script>
    export default {
        props: {
            comments: {
                type:     Array,
                required: true,
            },
            tip_id: {
                type:     Number,
                required: true,
            },
            permissions: {
                type:     Object,
                required: true,
            }
        },
        data() {
            return {
                commentList: this.comments,
                submitted:   false,
                loading:     false,
                form: {
                    tip_id:  this.tip_id,
                    comment: null,
                },
                updateForm: {
                    comment:    null,
                    comment_id: null,
                }
            }
        },
        methods: {
            getFlaggedClass(comment)
            {
                return comment.flagged ? 'fas text-danger' : 'far pointer text-muted';
            },
            canEdit(comment)
            {
                return comment.user.username == this.$page.props.app.user.username;
            },
            canDelete(comment)
            {
                return comment.user.username == this.$page.props.app.user.username || this.permissions.manage;
            },
            flagComment(comment)
            {
                axios.get(route('tips.comments.edit', comment.id))
                    .then(res => {
                        this.commentList = res.data;
                    }).catch(error => this.eventHub.$emit('axiosError', error));
            },
            editComment(comment)
            {
                this.updateForm.comment    = comment.comment;
                this.updateForm.comment_id = comment.id;
                this.$refs['edit-comment-modal'].show();
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
                        axios.delete(route('tips.comments.destroy', comment.id))
                            .then(res => {
                                this.commentList  = res.data;
                            }).catch(error => this.eventHub.$emit('axiosError', error));
                    }
                });

            },
            submitComment()
            {
                this.submitted = true;
                axios.post(route('tips.comments.store'), this.form)
                    .then(res => {
                        this.form.comment = null;
                        // this.commentList  = res.data;
                        this.submitted    = false;
                        this.$refs['validator'].reset();

                        console.log(res.data);
                    }).catch(error => this.eventHub.$emit('axiosError', error));
            },
            updateComment()
            {
                this.loading = true;
                axios.put(route('tips.comments.update', this.updateForm.comment_id), this.updateForm)
                    .then(res => {
                        this.commentList  = res.data;
                        this.$refs['edit-comment-modal'].hide();
                        this.loading = false;
                    }).catch(error => this.eventHub.$emit('axiosError', error));
            }
        },
    }
</script>
