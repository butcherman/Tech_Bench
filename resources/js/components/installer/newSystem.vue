<template>
    <b-form @submit="submitForm">
        <input type="hidden" name="_token" :value="token" />
        <fieldset>
            <b-form-select v-model="form.category" required>
                <option :value="null" selected>--- Select A Category ---</option>
                <option v-for="cat in categories" :value="cat.cat_id">{{cat.name}}</option>
            </b-form-select>
            <b-form-group label="System Name:" label-for="name" class="mt-2">
                <b-form-input id="name" name="name" v-model="form.name" type="text" v-validate="{regex: /^[a-zA-Z0-9_ ]*$/ }" required></b-form-input>
                <span class="invalid-feedback d-inline">{{ errors.first('name') }}</span>
            </b-form-group>
        </fieldset>
        <fieldset>
            <label>Customer Information to Gather</label> 
            <div ref="container">   
                <v-select v-for="n in range" :key="n" :options="dataTypes" v-model="form.dataOptions[n]" placeholder="Select An Option" class="pad-bottom" taggable></v-select>              
            </div>
            <span class="pointer float-right" @click="addRow">Add Row</span> 
        </fieldset>
        <b-button type="submit" variant="info" class="pad-top" block>Submit New System</b-button>
    </b-form>
</template>

<script>    
export default {
    props: [
        'cat_route',
        'dropdown',
        'submit_route',
        'finish_route',
    ],
    data() {
        return {
            range: 5,
            categories: [],
            form: {
                category: null,
                name: '',
                dataOptions: {},
            },
            dataTypes: JSON.parse(this.dropdown),
            token: window.techBench.csrfToken,
            dataOptions: {},
        }
    },
    created() 
    {
        this.getCategories();
    },
    methods: {
        getCategories()
        {
            axios.get(this.cat_route)
                .then(res => {
                    this.categories = res.data;
                })
                .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
        },
        addRow()
        {
            this.range += 1;
        },
        submitForm(e)
        {
            e.preventDefault();
            axios.post(this.submit_route, this.form)
                .then(res => {
                    console.log(res.data);
                    if(res.data.success == true)
                        {
                            window.location.href = this.finish_route;
                        }
                })
                .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
        }
    }
}
</script>
