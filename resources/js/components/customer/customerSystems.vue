<template>
    <div>
        <div v-if="error">
            <h5 class="text-center">Problem Loading Data...</h5>
        </div>
        <div v-else-if="loading">
            <h5 class="text-center">Loading Equipment</h5>
            <img src="/img/loading.svg" alt="Loading..." class="d-block mx-auto">
        </div>
        <div v-else>
            <div v-if="!systems.length" class="text-center">
                <h5>No Systems</h5>
                <b-button variant="primary" @click="addSystem">Add Equipment</b-button>
            </div>
            <div v-else>
                <b-tabs>
                    <b-tab v-for="sys in systems" :key="sys.cust_sys_id" :title="sys.system_types.name" class="pt-2">
                        <dl class="row" v-for="data in sys.system_data_fields" :key="data.field_id">
                            <dt class="col-sm-6 text-right mb-0">{{data.system_data_field_types.name}}:</dt>
                            <dd class="col-sm-6 text-left mb-0">{{data.pivot.value}}</dd>
                        </dl>
                        <div class="row justify-content-center">
                            <div class="col-8 col-sm-6 mb-2">
                                <b-button variant="primary" block @click="addSystem">Add Equipment</b-button>
                            </div>
                            <div class="col-8 col-sm-6">
                                <b-button variant="warning" block @click="editSystem(sys)">Edit Equipment</b-button>
                            </div>
                        </div>
                    </b-tab>
                </b-tabs>
            </div>
        </div>
        <b-modal id="new-system-modal" title="Add New Equipment" ref="newSystemModal" hide-footer>
            <div v-if="systemsLoading">
                <h5 class="text-center">Loading Equipment</h5>
                <img src="/img/loading.svg" alt="Loading..." class="d-block mx-auto">
            </div>
            <b-form v-else @submit="submitSystem" ref="newSystemForm">
                <b-form-group label="Equipment Type" label-size="lg">
                    <b-form-select v-model="selectedSystem" @change="populateDataFields">
                        <option :value="null">Please Select An Equipment Type</option>
                        <optgroup v-for="cat in systemTypes" :key="cat.cat_id" :label="cat.name">
                            <option v-for="sys in cat.system_types" :key="sys.sys_id" :value="sys">{{sys.name}}</option>
                        </optgroup>
                    </b-form-select>
                </b-form-group>
                <b-form-group label="Equipment Information" label-size="lg">
                    <b-form-group v-for="data in systemFields" :key="data.field_id" :label="data.system_data_field_types.name" :label-for="'sys-data-'+data.field_id">
                        <b-form-input :id="'sys-data-'+data.field_id" v-model="form['field_'+data.field_id]"></b-form-input>
                    </b-form-group>
                </b-form-group>
                <b-button type="submit" block variant="primary" class="mt-4" :disabled="button.disable">
                    <span class="spinner-border spinner-border-sm text-danger" v-show="button.disable"></span>
                    {{button.text}}
                </b-button>
            </b-form>
        </b-modal>
        <b-modal id="edit-system-modal" title="Add New Equipment" ref="editSystemModal" hide-footer>
            <b-form @submit="submitEditSystem" ref="editSystemForm">
                <h3 class="text-center">{{form.systemName}}</h3>
                <b-form-group label="Equipment Information" label-size="lg">
                    <b-form-group v-for="data in systemFields" :key="data.field_id" :label="data.system_data_field_types.name" :label-for="'sys-data-'+data.field_id">
                        <b-form-input :id="'sys-data-'+data.field_id" v-model="form['field_'+data.field_id]"></b-form-input>
                    </b-form-group>
                </b-form-group>
                <b-button type="submit" block variant="primary" class="mt-4" :disabled="button.disable">
                    <span class="spinner-border spinner-border-sm text-danger" v-show="button.disable"></span>
                    {{button.text}}
                </b-button>
                <b-button block variant="danger" class="mt-4" @click="deleteSystem">
                    <span class="spinner-border spinner-border-sm text-danger" v-show="button.delete.disable"></span>
                    {{button.delete.text}}
                </b-button>
            </b-form>
        </b-modal>
    </div>
</template>

<script>
export default {
    props: [
        'cust_id',
    ],
    data () {
        return {
            loading: true,
            systemsLoading: true,
            error: false,
            systems: [],
            systemTypes: [],
            systemFields: [],
            selectedSystem: '',
            form: {
                cust_id: this.cust_id,
                system: null,
            },
            button: {
                disable: false,
                text: 'Add Equipment',
                delete: {
                    disable: false,
                    text: 'Delete Equipment',
                }
            }
        }
    },
    created() {
        this.getSystems();
    },
    methods: {
        //  Get all of the systems currently attached to the customer
        getSystems()
        {
            axios.get(this.route('customer.systems.show', this.cust_id))
                .then(res => {
                    this.systems = res.data;
                    this.loading = false;
                }).catch(error => this.error = true);
        },
        //  Bring up the form to add a system
        addSystem()
        {
            this.button.disable = false;
            this.button.text = 'Add Equipment';
            this.$refs.newSystemModal.show();
            if(!this.systemTypes.length)
            {
                axios.get(this.route('customer.systems.index'))
                .then(res => {
                    this.systemTypes = res.data;
                    this.systemsLoading = false;
                }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
            }
        },
        submitSystem(e)
        {
            e.preventDefault();
            this.button.disable = true;
            this.button.text = 'Loading...';
            axios.post(this.route('customer.systems.store'), this.form)
                    .then(res => {
                        // console.log(res);
                        this.$refs.newSystemModal.hide();
                        this.getSystems();
                        this.selectedSystem = '';
                        this.form.system = null;
                        this.systemFields = [];
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
        },
        editSystem(sys)
        {
            this.form.systemName = sys.system_types.name;
            this.form.system = sys.sys_id;
            this.systemFields = sys.system_data_fields;
            this.selectedSystem = sys.cust_sys_id;
            this.button.disable = false;
            this.button.text = 'Update Equipment';
            for(var i=0; i < sys.system_data_fields.length; i++)
            {
                this.$set(this.form, 'field_'+sys.system_data_fields[i].field_id, sys.system_data_fields[i].pivot.value);
            }
            this.$refs.editSystemModal.show();
        },
        submitEditSystem(e)
        {
            e.preventDefault();
            this.button.disable = true;
            this.button.text = 'Loading...';
            axios.put(this.route('customer.systems.update', this.selectedSystem), this.form)
                    .then(res => {
                        this.loading = true;
                        this.getSystems();
                        this.$refs.newSystemModal.hide();
                        this.$refs.editSystemModal.hide();
                        this.selectedSystem = '';
                        this.form.system = null;
                        this.systemFields = [];
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));

        },
        //  In the new system form, populate the fields for the customer information
        populateDataFields()
        {
            //  Clear the form if no system is selected
            if(this.selectedSystem == null)
            {
                this.form.system = null;
                this.systemFields = [];
            }
            else
            {
                //  Check if the customer already has the selected system assigned
                if(this.checkForDuplicate(this.selectedSystem.sys_id))
                {
                    this.$bvModal.msgBoxOk('Customer already has this system.', {
                        title: 'Error',
                        size: 'md',
                        centered: true,
                    })
                        .then(res => {this.selectedSystem = null; this.systemFields = [];});
                }
                else
                {
                    this.form.system = this.selectedSystem.sys_id;
                    this.systemFields = this.selectedSystem.system_data_fields;
                }

            }
        },
        //  Check to see if the customer already has the system assigned
        checkForDuplicate(sysID)
        {
            for(var i=0; i < this.systems.length; i++)
            {
                if(this.systems[i].sys_id == sysID)
                {
                    return true;
                }
            }
            return false;
        },
        //  Delete a system
        deleteSystem()
        {
            this.$bvModal.msgBoxConfirm('Please confirm you want to delete '+this.form.systemName+' from this customer.', {
                title: 'Are You Sure?',
                size: 'md',
                okVariant: 'danger',
                okTitle: 'Yes',
                cancelTitle: 'No',
                centered: true,
            }).then(res => {
                if(res)
                {
                    this.loading = true;
                    this.$refs.editSystemModal.hide();
                    axios.delete(this.route('customer.systems.destroy', this.selectedSystem))
                        .then(res => {
                            this.getSystems();
                            this.selectedSystem = '';
                            this.form.system = null;
                            this.systemFields = [];
                        }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
                }
            });
        }
    }
}
</script>
