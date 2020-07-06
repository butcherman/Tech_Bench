<template>
    <b-overlay :show="loading">
        <template v-slot:overlay>
            <atom-spinner
                :animation-duration="1000"
                :size="60"
                color="#ff1d5e"
                class="mx-auto"
            />
            <h4 class="text-center">Processing</h4>
        </template>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h5 v-if="error" class="text-center text-danger"><i class="fas fa-exclamation-circle"></i> Unable to Load Comments</h5>
                <div v-else-if="comments.length" v-for="comment in comments" :key="comment.comment_id" class="border rounded p-4">
                    <div class="mb-2">{{comment.comment}}</div>
                    <div class="border-top text-secondary">
                        <i v-if="comment.edit" class="far fa-trash-alt text-danger pointer" @click="deleteComment(comment.comment_id)" title="Delete Comment" v-b-tooltip.hover></i>
                        {{comment.user.full_name}}
                        <div class="float-right">{{comment.created_at}}</div>
                    </div>
                </div>
                <div v-else>
                    <h5 class="text-center">No Comments Yet</h5>
                </div>
            </div>
        </div>
        <div class="row justify-content-center" v-if="!error">
            <div class="col-md-8">
                <h5 class="text-center">Have something to add?</h5>
                <b-form @submit.prevent="addComment" :validated="validated" novalidate ref="new-tip-comment-form">
                    <b-form-group>
                        <b-form-textarea
                            id="textarea"
                            v-model="form.comment"
                            placeholder="Comment on this tip..."
                            rows="3"
                            max-rows="6"
                            required
                        ></b-form-textarea>
                        <b-form-invalid-feedback>You must actually enter a comment, in order to comment...</b-form-invalid-feedback>
                    </b-form-group>
                    <form-submit
                        class="mt-3"
                        button_text="Add Comment"
                        :submitted="submitted"
                    ></form-submit>
                </b-form>
            </div>
        </div>
    </b-overlay>
</template>

<script>
    export default {
        props: {
            tip_id: {
                type    : Number,
                required: true,
            }
        },
        data() {
            return {
                error    : false,
                loading  : false,
                submitted: false,
                validated: false,
                comments : [],
                form     : {
                    comment: null,
                    tip_id : this.tip_id,
                }
            }
        },
        mounted() {
            this.getComments();
        },
        methods: {
            addComment()
            {
                if(this.$refs['new-tip-comment-form'].checkValidity() === false)
                {
                     this.validated = true;
                }
                else
                {
                    this.submitted    = true;
                    this.loading      = true;
                    axios.post(this.route('tips.comments.store'), this.form)
                        .then(res => {
                            this.submitted    = false;
                            this.form.comment = null;
                            this.getComments();
                        }).catch(error => this.eventHub.$emit('axiosError', error));
                }
            },
            getComments()
            {
                this.loading = true;
                axios.get(this.route('tips.comments.show', this.tip_id))
                    .then(res => {
                        this.comments = res.data;
                        this.loading = false;
                    }).catch(error => this.eventHub.$emit('axiosError', error));
            },
            deleteComment(id)
            {
                this.$bvModal.msgBoxConfirm('Please confirm that you want to delete this Comment.', {
                    title: 'Please Confirm',
                    size: 'sm',
                    buttonSize: 'sm',
                    okVariant: 'danger',
                    okTitle: 'YES',
                    cancelTitle: 'NO',
                    footerClass: 'p-2',
                    hideHeaderClose: false,
                    centered: true
                })
                .then(value => {
                    if(value)
                    {
                        axios.delete(this.route('tips.comments.destroy', id))
                        .then(res => {
                            this.getComments();
                        }).catch(error => this.error = true);
                    }
                })
                .catch(error => {
                    this.error = true;
                });
            }
        },
    }
</script>
