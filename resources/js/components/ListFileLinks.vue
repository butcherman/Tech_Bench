<template>
    <tbody>
        <tr>
            <td v-for="key in tabledata"></td>
        </tr>
    </tbody>
</template>

<script>
    export default {
//        mounted() {
//            console.log('Component mounted.')
//        }
        data: function() 
        {
            return 
            {
//                tableCols: ['name', 'num', 'expire'],
                tabledata: [
                    {name: 'a name', num: 5, expire:'2012-8-9'},
                    {name: 'another', num: 7, expire:'2012-8-9'},
                    {name: 'blah', num: 0, expire:'2012-8-9'}
                ]
            }
        }
    }
</script>
