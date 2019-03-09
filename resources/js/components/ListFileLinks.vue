<template>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th class="text-center"><input type="checkbox" class="check-all-links" v-model="selectAll" @click="select" /></th>
                <th>Link Name</th>
                <th># of Files</th>
                <th>Expire Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="5" class="text-center">
                    <button class="btn btn-info" id="delete-checked" @click="deleteChecked" :disabled="button.dis">{{button.text}}</button>
                </td>
            </tr>
        </tfoot>
        <tbody>
            <tr v-for="link in links">
                <td class="text-center"><input type="checkbox" class="check-link" :value="link.link_id" v-model="selected" /></td>
                <td>
                    <a :href="'/links/details/'+link.link_id+'/'+encodeURIComponent(link.link_name)">{{link.link_name}}</a>
                </td>
                <td>{{link.file_link_files_count}}</td>
                <td>{{link.expire}}</td>
                <td>
                    <a href="#edit-modal" title="Edit Link" data-toggle="modal" data-tooltip="tooltip" :data-id=link.link_id class="text-muted edit-link"><i class="fa fa-pencil" aria-hidden="true"></i></a> 
                    <a href="#edit-modal" title="Remove Link" data-toggle="modal" data-tooltip="tooltip" :data-id=link.link_id class="text-muted remove-link"><i class="fa fa-trash" aria-hidden="true"></i></a>
                </td>
            </tr>
            <tr v-if="links.length === 0">
                <td colspan="5" class="text-center">No File Links</td>
            </tr>
        </tbody> 
    </table>
</template>

<script>
    export default {
        data() {
            return {
                links:     [],
                link:      {},
                link_id:   '',
                selected:  [],
                selectAll: false,
                button: 
                {
                    text: 'Delete Checked',
                    dis:   false
                }
            }
        },
        created() {
            this.fetchLinks();
        },
        methods: {
            fetchLinks() 
            {
                axios.get('/links/data/1')
                    .then(res => {
                    this.links       = res.data;
                    this.button.text = 'Delete Checked';
                    this.button.dis  = false;
                })
                .catch(error => alert('There was an issue processing your request\nPlease try again later.'))
            },
            select()
            {
                this.selected = [];
                if(!this.selectAll)
                {
                    for(let i in this.links)
                    {
                        this.selected.push(this.links[i].link_id);
                    }
                }
            },
            deleteChecked()
            {
                var obj = this;
                
                if(obj.selected.length != 0)
                {
                    obj.button.text = 'Loading...';
                    obj.button.dis  = true;
                    this.selected.forEach(function(item)
                    {
                        axios.delete('/links/data/'+item)
                            .then(res => {
                                obj.fetchLinks();
                        })
                        .catch(error => alert('There was an issue processing your request\nPlease try again later.'))
                    });
                }
            }
        }
    }
</script>
