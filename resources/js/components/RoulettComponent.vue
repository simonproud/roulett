<template>
    <div class="row ">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">Выйграйте один из великолепных призов!</div>

                    <div class="card-body">
                        <b-button-group>
                            <b-button variant="success" @click="game">НАЧАТЬ</b-button>
                        </b-button-group>
                    </div>
                </div>
            </div>
        </div>
        <b-modal id="modal1" ref="myModalRef" title="BootstrapVue">
            <p class="my-4">Вы выйграли: {{data.item.item}} X {{data.count}}</p>
        </b-modal>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        data: function(){
            return {
                errors: [],
                data: {
                    item: ''
                }
            }
        },
        
        methods:{
            game(){
                    
                    var self = this;
                    axios.post(`/gamestart`, {   
                        game: 'start'                   
                    })
                    .then(response => {
                        self.data = response.data;
                        self.$refs.myModalRef.show()
                    })
                    .catch(e => {
                        this.errors.push(e)
                    });
            }
        }
    }
</script>
