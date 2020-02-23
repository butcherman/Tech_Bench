<template>

    <div class="row justify-content-center">
        <div class="col-sm-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-header">Comments</div>
                <div class="card-body">
                    <div v-if="error">
                        <h5 class="text-center">Problem Loading Comments...</h5>
                    </div>
                    <div v-else-if="loading">
                        <h5 class="text-center">Loading Comments</h5>
                        <img src="/img/loading.svg" alt="Loading..." class="d-block mx-auto">
                    </div>
                    <div v-show="comments" v-for="comment in comments" :key="comment.comment_id" class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    {{comment.comment}}
                                </div>
                                <div class="card-footer">
                                    <span v-if="user_id == comment.user_id" class="fas fa-trash-alt text-danger pointer" title="Delete Comment" v-b-tooltip:hover @click="deleteComment(comment.comment_id)"></span>
                                    By: {{comment.user.full_name}}
                                    <span class="float-right">Created: {{comment.created_at}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <b-form @submit="addComment">
                                <b-form-textarea
                                    id="textarea"
                                    v-model="form.comment"
                                    placeholder="Comment on this tip..."
                                    rows="3"
                                    max-rows="6"
                                ></b-form-textarea>
                                <div class="row">
                                    <div class="col text-center mt-3">
                                        <b-button type="submit" variant="primary" :disabled="button.disable">{{button.text}}</b-button>
                                    </div>
                                </div>
                            </b-form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: [
        'tip_id',
        'user_id',
    ],
    data() {
        return {
            error: false,
            loading: true,
            comments: [],
            form: {
                comment: '',
                tipID: this.tip_id,
            },
            button: {
                disable: false,
                text: 'Add Comment',
            }
        }
    },
    created()
    {
        this.getComments();
    },
    methods: {
        getComments()
        {
            axios.get(this.route('tip.comments.show', this.tip_id))
                .then(res => {
                    this.comments = res.data;
                    this.loading = false;
                }).catch(error => this.error = true);
        },
        addComment(e)
        {
            e.preventDefault();
            this.button.disable = true;
            this.button.text = 'Processing...';
            axios.post(this.route('tip.comments.store'), this.form)
                .then(res => {
                    this.button.disable = false;
                    this.button.text = 'Add Comment';
                    this.form.comment = '';
                    this.getComments();
                }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
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
                    axios.delete(this.route('tip.comments.destroy', id))
                    .then(res => {
                        this.getComments();
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
                }
            })
            .catch(error => {
                alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error);
            });
        }
    }
}
</script>
