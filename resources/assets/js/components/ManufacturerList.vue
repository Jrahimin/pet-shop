<template>
    <div id="manufacturer">
    <div class="box box-primary" style="padding:20px" id="list">
        <div class="row">
            <div class="col-md-12 table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                    <tr>
                        <th>Logo</th>
                        <th>Logotype2</th>
                        <th>Title</th>
                        <th></th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="manufacturer in manufacturers" >
                        <td>@{{ manufacturer.img }}</td>
                        <td>@{{ manufacturer.img2 }}</td>
                        <td>@{{ manufacturer.title }}</td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="myModal" class="modal fade"  >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Confirmation </h4>
                    </div>
                    <div class="modal-body">
                        <p> Are you sure?</p>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" @click="deleteBanner">@lang('Yes')</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('No')</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        data(){
            return{
              manufacturers:[]
            }
        },
        created() {

        },
        methods:{
            getBanners()
            {
                axios.get('get-manufacturers').then(response=>{
                    //console.log(response.data)
                    this.manufacturers=response.data;
                })
            },

        }
    }
</script>

<style>

</style>