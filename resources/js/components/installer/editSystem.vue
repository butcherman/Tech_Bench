<template>
    <b-form @submit="submitForm">
        <input type="hidden" name="_token" :value="token" />
        <fieldset>
            <b-form-group label="System Name:" label-for="name" class="mt-2">
                <b-form-input id="name" name="name" v-model="form.name" type="text" v-validate="{regex: /^[a-zA-Z0-9_ ]*$/ }" required></b-form-input>
                <span class="invalid-feedback d-inline">{{ errors.first('name') }}</span>
            </b-form-group>
        </fieldset>
        <fieldset>
            <label>Customer Information to Gather</label> 
            <div ref="container">  
                <draggable group="existing" @start="drag=true" @end="drag=false" v-bind="dragOptions" :list="form.dataOptions">
                   <b-form-input v-for="label in form.dataOptions" :key="label" type="text" :value="label" class="pad-bottom pointer" disabled></b-form-input>
                </draggable>
                <v-select v-for="n in range" :key="n" :options="dataTypes" v-model="form.newDataOptions[n]" placeholder="Select An Option" class="pad-bottom" taggable></v-select>                    
            </div>
            <span class="pointer float-right" @click="addRow">Add Row</span> 
        </fieldset>
        <b-button type="submit" variant="info" class="pad-top" block>Update System</b-button>
    </b-form>
</template>

<script>    
export default {
    props: [
        'dropdown',
        'get_route',
        'submit_route',
        'finish_route',
    ],
    data() {
        return {
            range: 1,
            form: {
                name: '',
                dataOptions: [],
                newDataOptions: {},
            },
            dataTypes: JSON.parse(this.dropdown),
            token: window.techBench.csrfToken,
            dragOptions: {
                animation: 200,
                ghostClass: "ghost"
            }
        }
    },
    created() 
    {
        this.getSystem();
    },
    methods: {
        getSystem()
        {
            axios.get(this.get_route)
                .then(res => {
                    this.form.name = res.data.name;
                    this.form.dataOptions = res.data.data;
                    console.log(res.data.data);
                })
                .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
        },
        addRow()
        {
            this.range += 1;
        },
        validate()
        {
            this.$validator.validateAll();
            console.log('triggered');
        },
        submitForm(e)
        {
            e.preventDefault();
            axios.put(this.submit_route, this.form)
                .then(res => {
                    if(res.data.success == true)
                    {
                        window.location.href = this.finish_route;
                    }
                })
//                .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
                .catch(error => {
                    console.log(error);
                });
        }
    }
}
</script>
