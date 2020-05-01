<template>
    <div class="card">
        <div class="card-header">
            Link Instructions:
            <b-button pill variant="primary" size="sm" class="float-right" v-b-modal.edit-instructions-modal>
                <i class="far fa-edit"></i>
                Edit Instructions
            </b-button>
        </div>
        <div class="card-body">
            <div v-if="error">
                <h5 class="text-center text-danger"><i class="fas fa-exclamation-circle"></i> Unable to load Instructions...</h5>
            </div>
            <div v-else-if="loading">
                <atom-spinner
                    :animation-duration="1000"
                    :size="60"
                    color="#ff1d5e"
                    class="mx-auto"
                />
            </div>
            <div v-else v-html="note"></div>
        </div>
        <b-modal
            id="edit-instructions-modal"
            title="Edit Link Instructions"
            ref="editInstructionsModal"
            size="lg"
            hide-footer
            centered
            @shown="form.open = true"
            @hidden="resetForm"
        >
            <b-form @submit="validateForm" ref="editInstructionsForm">
                <editor v-if="form.open" :init="{plugins: 'autolink', height:500}" id="instruction-details" v-model="form.instructions"></editor>
                <atom-spinner
                    v-else
                    :animation-duration="1000"
                    :size="60"
                    color="#ff1d5e"
                    class="mx-auto"
                />
                <form-submit
                    button_text="Update Instructions"
                    :submitted="submitted"
                ></form-submit>
            </b-form>
        </b-modal>
    </div>
</template>

<script>
    export default {
        props: {
            link_id: {
                type:     Number,
                required: true,
            },
            instructions: {
                type:     String,
                required: false,
                default:  null
            }
        },
        data() {
            return {
                error:     false,
                loading:   false,
                note:      this.instructions != null ? this.instructions : '<h5 class="text-center">No Instructions</h5>',
                submitted: false,
                form: {
                    open:         false,
                    instructions: this.instructions,
                }
            }
        },
        methods: {
            getInstructions()
            {
                axios.get(this.route('links.getInstructions', this.link_id))
                    .then(res => {
                        this.note = res.data.note != null ? res.data.note : '<h5 class="text-center">No Instructions</h5>';
                        this.loadDone = true;
                        this.form.instructions = res.data.note;
                    }).catch(error => { this.error = true; });
            },
            validateForm(e)
            {
                e.preventDefault();

                this.submitted = true;
                axios.post(this.route('links.submitInstructions', this.link_id), this.form)
                    .then(res => {
                        this.getInstructions();
                        this.$refs['editInstructionsModal'].hide();
                    }).catch(error =>
                        this.$bvModal.msgBoxOk('Update Instructions operation failed.  Please try again later.')
                    );
            },
            resetForm()
            {
                this.submitted = false;
                this.form.open = false;
                this.form.instructions = this.note;
            }
        }
    }
</script>
