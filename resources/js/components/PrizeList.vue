<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <b-table striped hover :items="items" :fields="fields" >
                        <template slot="buttons" slot-scope="row">
                           <b-button variant="success" @click="convert(row.item.id)" v-if="row.item.prize == 'Деньги'">Конвертировать x3.25</b-button>
                        </template>
                    </b-table>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['prizelist'],
        mounted() {
            console.log(JSON.parse(this.prizelist));
        },
        data: function(){
            return {
                errors: [],
                items: JSON.parse(this.prizelist),
                fields: ['prize', 'countp', 'buttons'],
            }
        },
        
        methods:{
            convert(data){
               
                    var self = this;
                    axios.post(`/convert`, {   
                        id: data                   
                    })
                    .then(response => {
                       if(response.data.status == 'success'){
                            location.reload();
                       }
                    })
                    .catch(e => {
                        this.errors.push(e)
                    });
            }
        }
        
    }
</script>
