<template>
    <b-overlay :show="loading">
        <template v-slot:overlay>
            <atom-spinner
                :animation-duration="1000"
                :size="60"
                color="#ff1d5e"
                class="mx-auto"
            />
            <h4 class="text-center">Loading...</h4>
        </template>
        <div v-if="error" class="row justify-content-center">
            <div class="col-md-8">
                <h5 class="text-center text-danger"><i class="fas fa-exclamation-circle"></i> Unable to Load Comments</h5>
            </div>
        </div>
        <div v-else-if="comments.length" class="row justify-content-center grid-margin" v-for="comment in comments" :key="comment.comment_id">
            <div class="col-md-8 border rounded p-4">
                <div>{{comment.comment}}</div>
                <div class="border-top text-secondary">
                    <i v-if="comment.edit" class="far fa-trash-alt text-danger pointer" @click="deleteComment(comment.comment_id)" title="Delete Comment" v-b-tooltip.hover></i>
                    {{comment.user.full_name}}
                    <div class="float-right">{{comment.created_at}}</div>
                </div>
            </div>
        </div>
        <div v-else class="row justify-content-center">
            <div class="col-md-8">
                <h5 class="text-center">No Comments Yet</h5>
            </div>
        </div>
        <div class="row justify-content-center" v-if="!error">
            <div class="col-md-8">
                <h5 class="text-center">Have something to add?</h5>
                <b-form @submit="addComment">
                    <b-form-textarea
                        id="textarea"
                        v-model="form.comment"
                        placeholder="Comment on this tip..."
                        rows="3"
                        max-rows="6"
                    ></b-form-textarea>
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
                type: Number,
                required: true,
            },
            who_am_i: {
                type:     String,
                required: false,
                default:  null,
            }
        },
        data() {
            return {
                loading: false,
                error: false,
                comments: {},
                submitted: false,
                form: {
                    tip_id: this.tip_id,
                    comment: null,
                },
            }
        },
        created() {
            //
        },
        mounted() {
             this.getComments();
        },
        computed: {
             //
        },
        watch: {
             //
        },
        methods: {
            getComments()
            {
                this.loading = true;
                axios.get(this.route('tip.comments.show', this.tip_id))
                    .then(res => {
                        this.comments = res.data;
                        this.loading = false;
                        console.log(this.comments);
                    }).catch(error => { this.error = true, this.loading = false });
            },
            addComment(e)
            {
                e.preventDefault();
                console.log(this.form);
                if(this.form.comment)
                {
                    this.submitted = true;
                    axios.post(this.route('tip.comments.store'), this.form)
                        .then(res => {
                            this.submitted = false;
                            this.form.comment = '';
                            this.getComments();
                        }).catch(error => alert(error));
                }
            },
            deleteComment(commentID)
            {
                console.log(commentID);
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
                        axios.delete(this.route('tip.comments.destroy', commentID))
                        .then(res => {
                            this.getComments();
                        }).catch(error => this.error = true);
                    }
                })
                .catch(error => {
                    this.error = true;
                });
            }
        }
    }
</script>
