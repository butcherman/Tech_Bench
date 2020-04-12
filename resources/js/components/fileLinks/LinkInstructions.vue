<template>
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-header">
                    Link Instructions:
                    <b-button pill variant="primary" size="sm" class="float-right" v-b-modal.edit-instructions-modal>Edit Instructions</b-button>
                </div>
                <div class="card-body">
                    <div v-if="error">
                        <h5 class="text-center text-danger"><i class="fas fa-exclamation-circle"></i> Unable to load Instructions...</h5>
                    </div>
                    <hollow-dots-spinner v-else-if="!loadDone"
                        :animation-duration="1000"
                        :dot-size="15"
                        :dots-num="3"
                        color="#ff1d5e"
                        class="mx-auto"
                    />
                    <div v-else v-html="note"></div>
                </div>
            </div>
        </div>
        <b-modal id="edit-instructions-modal" title="Edit Link Instructions" ref="editInstructionsModal" hide-footer centered size="lg">
            <b-form @submit="validateForm" ref="editInstructionsForm">
                <editor v-if="form.open" :init="{plugins: 'autolink', height:500}" id="instruction-details" v-model="form.instructions"></editor>
                <!-- <img v-else src="/img/loading.svg" alt="Loading..." class="d-block mx-auto"> -->
                <hollow-dots-spinner v-else
                    :animation-duration="1000"
                    :dot-size="15"
                    :dots-num="3"
                    color="#ff1d5e"
                    class="mx-auto"
                />
                <form-submit
                    :button_text="buttonText"
                    :submitted="submitted"
                ></form-submit>
            </b-form>
        </b-modal>
    </div>
</template>

<script>
    export default {
        props: [
            'link_id',
        ],
        data() {
            return {
                loadDone: false,
                error: false,
                note: '<h5 class="text-center">No Instructions</h5>',
                validated: false,
                submitted: false,
                buttonText: 'Update Instructions',
                form: {
                    open: false,
                    instructions: '',
                }
            }
        },
        created()
        {
            this.getInstructions();
            this.$root.$on('bv::modal::shown', (bvEvent, modalID) => {

                this.form.open = true;
            });
            this.$root.$on('bv::modal::hidden', (bvEvent, modalID) => {
                this.form.open = false;
            });
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
                        this.submitted = false;
                    }).catch(error =>
                        this.$bvModal.msgBoxOk('Update Instructions operation failed.  Please try again later.')
                    );
            },
        }
    }
</script>
