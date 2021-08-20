<template>
    <div>
        <div class="row grid-margin">
            <div class="col-12">
                <h4 class="text-center text-md-left">Edit Tech Tip</h4>
            </div>
        </div>
        <div class="row grid-margin">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <b-overlay :show="submitted">
                            <template #overlay>
                                <progress-bar :percent-done="fileProgress"></progress-bar>
                            </template>
                            <ValidationObserver v-slot="{handleSubmit}">
                                <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                                    <text-input v-model="form.subject" label="Subject" name="subject" placeholder="Enter A Descriptive Subject" rules="required"></text-input>
                                    <dropdown-input
                                        v-model="form.tip_type_id"
                                        label="Tip Type"
                                        rules="required"
                                        name="tipType"
                                        :options="tipTypes"
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
                                    <div class="row grid-margin justify-content-center">
                                        <div class="col">
                                            <div class="card rounded">
                                                <div class="card-body">
                                                    <div class="card-title">Attachments:</div>
                                                    <ul class="list-group px-5">
                                                        <li v-for="(file, key) in form.fileList" :key="file.file_id" class="list-group-item">
                                                            <a :href="route('download', [file.file_id, file.file_name])">{{file.file_name}}</a>
                                                            <b-badge class="float-right pointer" variant="danger" @click="deleteFile(key)" title="Remove File" v-b-tooltip.hover><i class="fas fa-trash-alt"></i></b-badge>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center mb-3">
                                        <b-button variant="info" v-b-toggle.add-file-block>Add File</b-button>
                                        <b-button variant="info" v-b-toggle.advanced-options-block>Advanced Options</b-button>
                                    </div>
                                    <b-collapse id="add-file-block">
                                        <dropzone-upload
                                            ref="dropzone-upload"
                                            :url="route('tech-tips.update', tipData.tip_id)"
                                            :max-files="5"
                                            @upload-canceled="canceled"
                                            @upload-progress="updateProgressbar"
                                            @completed="uploadDone"
                                            @validation-error="canceled"
                                        ></dropzone-upload>
                                    </b-collapse>
                                    <b-collapse id="advanced-options-block">
                                        <div class="row justify-content-center">
                                            <div class="col-md-3">
                                                <b-form-checkbox v-model="form.resendNotif" switch>
                                                    Send Notification of Update
                                                    <i class="far fa-question-circle pointer" title="More Information" v-b-popover.hover.top="'When enabled, Tech Tip will be created, but no notifications will be sent to other users'" ></i>
                                                </b-form-checkbox>
                                                <b-form-checkbox v-model="form.sticky" switch>
                                                    Make Sticky Tip
                                                    <i class="far fa-question-circle pointer" title="More Information" v-b-popover.hover.top="'Sticky Tech Tips will always be at the top of the search list'" ></i>
                                                </b-form-checkbox>
                                            </div>
                                        </div>
                                    </b-collapse>
                                    <submit-button class="mt-2" button_text="Update Tech Tip" :submitted="submitted"></submit-button>
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
            tipData: {
                type: Object,
                required: true,
            },
            tipTypes: {
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
                fileProgress: 0,
                form: {
                    subject:      this.tipData.subject,
                    tip_type_id:  this.tipData.tip_type_id,
                    details:      this.tipData.details,
                    resendNotif:  false,
                    sticky:       this.tipData.sticky,
                    equipment:    this.tipData.equipment_type,
                    fileList:     this.tipData.file_uploads,
                    removedFiles: []
                },
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
            //
            submitForm()
            {
                this.submitted = true;
                if(this.$refs['dropzone-upload'].getFileCount() > 0)
                {
                    this.$refs['dropzone-upload'].process(this.form);
                }
                else
                {
                    // this.uploadDone();
                    console.log(this.form);
                }
            },
            //  If a file was canceled during upload, go back to form
            canceled()
            {
                // this.submitted = false;
                // this.loading   = false;
            },
            //  Update the overlay's progress bar
            updateProgressbar(progress)
            {
                // this.fileProgress = progress;
            },
            //  File upload is completed
            uploadDone()
            {
                this.$inertia.put(route('tech-tips.store', tipData.tip_id), this.form);
            },
            deleteFile(fileKey)
            {
                this.form.removedFiles.push(this.form.fileList[fileKey].file_id);
                this.form.fileList.splice(fileKey, 1);
            }
        }

    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
