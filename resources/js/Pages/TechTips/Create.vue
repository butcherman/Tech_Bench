<template>
    <div>
        <div class="row grid-margin">
            <div class="col-12">
                <h4 class="text-center text-md-left">Tech Tips</h4>
            </div>
        </div>
        <div class="row grid-margin">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <b-overlay :show="submitted">
                            <template #overlay>
                                <progress-bar v-if="uploading" :percent-done="fileProgress" />
                                <form-loader v-else />
                            </template>
                            <ValidationObserver v-slot="{handleSubmit}">
                                <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                                    <text-input v-model="form.subject" label="Subject" name="subject" placeholder="Enter A Descriptive Subject" rules="required"></text-input>
                                    <dropdown-input
                                        v-model="form.tip_type_id"
                                        label="Tip Type"
                                        rules="required"
                                        name="tipType"
                                        :options="tip_types"
                                        text-field="description"
                                        value-field="tip_type_id"
                                        placeholder="Select A Tip Type"
                                    ></dropdown-input>
                                    <ValidationProvider v-slot="v" rules="required">
                                        <b-form-group label="Equipment Types:" label-for="equipment">
                                            <multiselect
                                                v-model="form.equipment"
                                                placeholder="Select At Least One Equipment Type"
                                                group-values="equipment_type"
                                                group-label="name"
                                                label="name"
                                                track-by="equip_id"
                                                :options="equipment"
                                                :multiple="true"
                                                :allow-empty="false"
                                                :group-select="true"
                                                required
                                            ></multiselect>
                                            <b-form-invalid-feedback :state="false">{{v.errors[0]}}</b-form-invalid-feedback>
                                        </b-form-group>
                                    </ValidationProvider>
                                    <text-editor
                                        v-model="form.details"
                                        rules="required"
                                        label="Tip Details"
                                        :allow_image="true"
                                        name="tipDetails"
                                    ></text-editor>
                                    <div class="text-center mb-3">
                                        <b-button variant="info" v-b-toggle.add-file-block>Add File</b-button>
                                        <b-button variant="info" v-b-toggle.advanced-options-block>Advanced Options</b-button>
                                    </div>
                                    <b-collapse id="add-file-block">
                                        <dropzone-upload
                                            ref="dropzone-upload"
                                            disk="tips"
                                            :max-files="5"
                                            @upload-canceled="canceled"
                                            @upload-progress="updateProgressbar"
                                            @completed="createTip"
                                            @validation-error="canceled"
                                        ></dropzone-upload>
                                    </b-collapse>
                                    <b-collapse id="advanced-options-block">
                                        <div class="row justify-content-center">
                                            <div class="col-md-3">
                                                <b-form-checkbox v-model="form.noEmail" switch>
                                                    Supress Notification
                                                    <i class="far fa-question-circle pointer" title="More Information" v-b-popover.hover.top="'When enabled, Tech Tip will be created, but no notifications will be sent to other users'" ></i>
                                                </b-form-checkbox>
                                                <b-form-checkbox v-model="form.sticky" switch>
                                                    Make Sticky Tip
                                                    <i class="far fa-question-circle pointer" title="More Information" v-b-popover.hover.top="'Sticky Tech Tips will always be at the top of the search list'" ></i>
                                                </b-form-checkbox>
                                            </div>
                                        </div>
                                    </b-collapse>
                                    <submit-button class="mt-2" button_text="Create Tech Tip" :submitted="submitted"></submit-button>
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
    import Multiselect from 'vue-multiselect';

    export default {
        layout: App,
        components: { Multiselect },
        props: {
            tip_types: {
                type:     Array,
                required: true,
            },
            equipment: {
                type:     Array,
                required: true,
            }
        },
        data() {
            return {
                submitted:    false,
                uploading:    false,
                fileProgress: 0,
                form: this.$inertia.form({
                    subject:     null,
                    tip_type_id: null,
                    details:     null,
                    noEmail:     false,
                    sticky:      false,
                    equipment:   [],
                }),
            }
        },
        created() {
            //
        },
        mounted() {
             //
        },
        computed: {
             //
        },
        watch: {
             //
        },
        methods: {
            submitForm()
            {
                this.submitted = true;
                if(this.$refs['dropzone-upload'].getFileCount() > 0)
                {
                    this.uploading = true;
                    this.$refs['dropzone-upload'].process();
                }
                else
                {
                    this.createTip();
                }
            },
            createTip()
            {
                this.uploading = false;
                this.form.post(route('tech-tips.store'));
            },
             //  If a file was canceled during upload, go back to form
            canceled()
            {
                this.submitted = false;
                this.loading   = false;
            },
            //  Update the overlay's progress bar
            updateProgressbar(progress)
            {
                this.fileProgress = progress;
            },
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css" />
