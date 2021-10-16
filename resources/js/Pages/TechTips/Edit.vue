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
                                    <div class="row grid-margin justify-content-center">
                                        <div class="col">
                                            <div class="card rounded">
                                                <div class="card-body">
                                                    <div class="card-title">Attachments:</div>
                                                    <ul class="list-group px-5">
                                                        <li v-if="form.fileList.length == 0" class="list-group-item text-center">No Files</li>
                                                        <li v-else v-for="(file, key) in form.fileList" :key="file.file_id" class="list-group-item">
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
                                            disk="tips"
                                            :folder="data.tip_id"
                                            :max-files="5"
                                            @upload-canceled="canceled"
                                            @upload-progress="updateProgressbar"
                                            @completed="updateTip"
                                            @validation-error="canceled"
                                        ></dropzone-upload>
                                    </b-collapse>
                                    <b-collapse id="advanced-options-block">
                                        <div class="row justify-content-center">
                                            <div class="col-md-3">
                                                <b-form-checkbox v-model="form.resendNotif" switch>
                                                    Send Notification of Update
                                                    <i class="far fa-question-circle pointer" title="More Information" v-b-popover.hover.top="'When enabled, an email will be sent letting others know this Tech Tip was updated'" ></i>
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
            data: {
                type:     Object,
                required: true,
            },
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
                    subject:      this.data.subject,
                    tip_type_id:  this.data.tip_type_id,
                    details:      this.data.details,
                    resendNotif:  false,
                    sticky:       this.data.sticky,
                    equipment:    this.data.equipment_type,
                    fileList:     this.data.file_uploads,
                    removedFiles: [],
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
                    this.updateTip();
                }
            },
            updateTip()
            {
                console.log('update tip');

                this.form.put(route('tech-tips.update', this.data.tip_id));
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
            deleteFile(file)
            {
                console.log(file);

                this.form.removedFiles.push(this.form.fileList[file].file_id);
                this.form.fileList.splice(file, 1);
            }
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css" />
