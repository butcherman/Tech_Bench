<template>
    <div>
        <div class="row justify-content-center">
            <div v-if="!custSystems.length" class="col-12">
                <h5 class="text-center">No Systems.  Please Add One.</h5>
                <div class="row justify-content-center">
                    <div class="col-8 col-sm-4 mb-2">
                        <b-button v-b-modal.new-sys-modal variant="info" block>Add System</b-button>
                    </div>
                </div>
            </div>
            <div v-else class="col-12">
                <b-tabs>
                    <b-tab v-for="sys in custSystems" :key="sys.cust_sys_id" :title="sys.name">
                        <dl class="row justify-content-center pad-top" v-for="data in sys.data">
                            <dt class="col-sm-6 text-left text-sm-right">{{data.name}}:</dt>
                            <dd class="col-sm-6">{{data.value}}</dd>
                        </dl>
                        <div class="row justify-content-center">
                            <div class="col-8 col-sm-4 mb-2">
                                <b-button v-b-modal.new-sys-modal variant="info" block>Add System</b-button>
                            </div>
                            <div class="col-8 col-sm-4">
                                <b-button variant="warning" @click="editSystem(sys)" block>Edit System</b-button>
                            </div>
                        </div>
                    </b-tab>
                </b-tabs>
            </div>
        </div>
        <b-modal id="new-sys-modal" title="Add New System" ref="newSysModal" hide-footer>
            <b-form @submit="addSystem">
                <b-form-select v-model="form.system" @change="populateData">
                    <option :value="null">Please Select A System Type</option>
                    <optgroup v-for="(sysData, key) in JSON.parse(sys_list)" :label="key">
                        <option v-for="sys in sysData" :value="sys.sys_id">{{sys.name}}</option>
                    </optgroup>
                    
                </b-form-select>
                <span class="invalid-feedback d-inline" v-if="dupSys">
                    <strong>Customer Already Has This System</strong>
                </span>
                <b-form-group
                    v-for="field in sysFields"
                    :key="field.order"
                    :label="field.name"
                    :label-for="'field-id-'+field.field_id"
                >
                    <b-form-input
                        :id="'field-id-'+field.field_id"
                        type="text"
                        v-model="form.fieldData[field.field_id]"
                    ></b-form-input>
                </b-form-group>
                <b-button type="submit" variant="info" class="pad-top" block>Submit New System</b-button>
            </b-form>
        </b-modal>
        <b-modal id="edit-sys-modal" title="Edit System" ref="editSysModal" hide-footer>
            <b-form @submit="updateSystem">
                <b-form-group
                    v-for="(field, key) in form.fieldData"
                    :key="field.order"
                    :label="field.name"
                    :label-for="'field-id-'+key"
                >
                    <b-form-input
                        :id="'field-id-'+key"
                        type="text"
                        v-model="form.fieldData[key].value"
                        :disabled="key === 0"
                    ></b-form-input>
                </b-form-group>
                <b-button type="submit" variant="info" class="pad-top" block>Update System</b-button>
            </b-form>
            <click-confirm>
                <b-button variant="danger" class="pad-top" @click="deleteSystem">Delete System</b-button>
            </click-confirm>
        </b-modal>
    </div>
</template>

<script>
export default {
    props: [
        'cust_id',
        'get_sys_route',
        'sys_data_route',
        'new_sys_route',
        'edit_sys_route',
        'sys_list',
    ],
    data () {
        return {
            form: {
                custID: this.cust_id,
                system: null,
                fieldData: [],
            },
            custSystems: [],
            sysFields: [],
            editName: '',
            dupSys: false,
        }
    },
    created() {
        this.getSystems();
    },
    methods: {
        //  Get all of the sysstems currently attached to the customer
        getSystems()
        {
            axios.get(this.get_sys_route)
                .then(res => {
                    this.custSystems = res.data;
                    console.log(this.custSystems);
                }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
        },
        //  Get the data that should be gathered for the selected system
        populateData()
        {            
            //  verify that the customer does not already have the system
            var dup = false;
            var sys = this.form.system
            this.custSystems.forEach(function(el)
            {
                if(el.sys_id == sys)
                {
                    dup = true;
                }
//                console.log(el.sys_id);
            });
                
//                console.log(dup);
            
            if(dup)
            {
                this.sysFields = [];
                this.dupSys = true;
            }
            else
            {
                this.dupSys = false;
                axios.get(this.sys_data_route.replace(':id', this.form.system))
                    .then(res => {
                        this.sysFields = res.data;
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
            }
        },
        //  Submit the new system for the customer to the DB
        addSystem(e)
        {
            e.preventDefault();
                        
            axios.post(this.new_sys_route, this.form)
                .then(res => {
                    if(res.data.success == true)
                    {
                        this.$refs.newSysModal.hide();
                        this.getSystems();
                        this.form.system = null;
                        this.form.fieldData = [];
                        this.sysFields = [];
                    }
                }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
        },
        //  Edit the selected customers system
        editSystem(sys)
        {
            this.editName = sys.name;
            this.form.system = sys.cust_sys_id;
            this.form.fieldData = sys.data;
            this.$refs.editSysModal.show();
        },
        //  Submit the edit system form
        updateSystem(e)
        {
            e.preventDefault();
                    
            axios.put(this.edit_sys_route.replace(':id', this.form.custID), this.form)
                .then(res => {
                    console.log(res);
                    if(res.data.success)
                    {
                        this.$refs.editSysModal.hide();
                        this.getSystems();
                        this.form.system = null;
                        this.form.fieldData = [];
                        this.sysFields = [];
                    }
                }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
        },
        //  Delete the system attached to the customer
        deleteSystem()
        {
            console.log(this.form.system);
            axios.delete(this.edit_sys_route.replace(':id', this.form.system))
                .then(res => {
                    if(res.data.success)
                    {
                        this.$refs.editSysModal.hide();
                        this.getSystems();
                        this.form.system = null;
                        this.form.fieldData = [];
                        this.sysFields = [];
                    }
                }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
        }
    }
}
</script>
