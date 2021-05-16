<template>
    <b-button pill variant="light" size="sm" @click="$refs['edit-file-modal'].show();" title="Edit" v-b-tooltip.hover>
        <i class="fas fa-pencil-alt"></i>
        <b-modal
            ref="edit-file-modal"
            title="Edit File"
            hide-footer
        >
        <b-overlay :show="loading">
            <template #overlay>
                <form-loader></form-loader>
            </template>
            <ValidationObserver v-slot="{handleSubmit}">
                <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                    <text-input
                        label="Name"
                        name="name"
                        v-model="form.name"
                        rules="required"
                        placeholder="Enter a Descriptive Name"
                    ></text-input>
                    <dropdown-input
                        label="File Type"
                        name="type"
                        v-model="form.type"
                        rules="required"
                        :options="file_types"
                        value-field="description"
                        text-field="description"
                        placeholder="What Type of File Is This?"
                    ></dropdown-input>
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <b-form-checkbox v-model="form.shared" switch>Share File Across All Sites</b-form-checkbox>
                        </div>
                    </div>
                    <submit-button button_text="Update File" :submitted="submitted"></submit-button>
                </b-form>
            </ValidationObserver>
        </b-overlay>
        </b-modal>
    </b-button>
</template>

<script>
    export default {
        props: {
            cust_id: {
                type:     Number,
                required: true,
            },
            data: {
                type:     Object,
                required: true,
            }
        },
        data() {
            return {
                loading:   false,
                submitted: false,
                form: {
                    cust_id: this.cust_id,
                    name:    this.data.name,
                    type:    this.data.file_type,
                    shared:  this.data.shared,
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
            file_types() {
                return this.$page.props.file_types;
            }
        },
        watch: {
            //
        },
        methods: {
            //
            submitForm()
            {
                this.submitted = true;
                this.loading   = true;
                this.$inertia.put(route('customers.files.update', this.data.cust_file_id), this.form, {onFinish: () => {
                    console.log('done');
                    this.$refs['edit-file-modal'].hide();
                    this.loading   = false;
                    this.submitted = false;
                    this.$emit('completed');
                }});
            }
        },
    }
</script>
