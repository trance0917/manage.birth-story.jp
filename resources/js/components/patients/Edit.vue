<template>
    <form  method="get" ref="form">
        {{params.tbl_patient.name}}
        <div class="w-[200px]">
            <div class="text-[14px] font-bold text-center mb-[3px]">プレゼントの動画</div>
            <label class="relative" for="present_movie_path" v-if="!params.tbl_patient.present_movie_path">
                <div class="choice py-[40px] text-slate-400 text-[16px] bg-slate-100 block text-center border border-dashed border-slate-300">動画を設定</div>
                <i v-if="'present_movie_path'==loading_input_key"
                   class="fa-solid fa-spinner fa-spin text-slate-300 text-[40px] absolute top-[calc(50%-20px)] left-[calc(50%-20px)]"></i>
            </label>
            <div v-else>
                <video controls width="320" height="240">
                    <source :src="'/storage/patients/'+params.tbl_patient.tbl_patient_id+'_'+params.tbl_patient.code+'/'+params.tbl_patient.present_movie_path">
                    <p>動画を再生するには、videoタグをサポートしたブラウザが必要です。</p>
                </video>
                <label for="present_movie_path">変更</label>
            </div>

            <input type="file" id="present_movie_path" accept="video/*" v-on:change="save_movie($event,'present_movie_path')" />
            <div class="bg-green-500/20" :style="loading_progress.present_movie_path"></div>
            mp4,200mb以下

        </div>

        <div class="w-[200px]">
            <div class="text-[14px] font-bold text-center mb-[3px]">プレゼントの画像</div>
            <label class="relative" for="present_photoart_path" v-if="!params.tbl_patient.present_photoart_path">
                <div class="choice py-[40px] text-slate-400 text-[16px] bg-slate-100 block text-center border border-dashed border-slate-300">画像を設定</div>
                <i v-if="'present_photoart_path'==loading_input_key"
                   class="fa-solid fa-spinner fa-spin text-slate-300 text-[40px] absolute top-[calc(50%-20px)] left-[calc(50%-20px)]"></i>
            </label>
            <div v-else>
                <img :src="'/storage/patients/'+params.tbl_patient.tbl_patient_id+'_'+params.tbl_patient.code+'/'+params.tbl_patient.present_photoart_path">
                <label for="present_photoart_path">変更</label>
            </div>

            <input type="file" id="present_photoart_path" accept="image/*" v-on:change="save_movie($event,'present_photoart_path')" />
            <div class="bg-green-500/20" :style="loading_progress.present_photoart_path"></div>
            png,jpgのみ10MB以下
            <!--            <div class="error text-center" v-if="errors['tbl_patient.tbl_patient_mediums.first_cry']">@{{ errors['tbl_patient.tbl_patient_mediums.first_cry'][0] }}</div>-->
        </div>

    </form><!--end em-filter-->



</template>

<script>

export default {
    name: "patients-edit",

    data(){
        return {
            params:params,

            loading_input_key:'',
            loading_progress:{
                present_movie_path:'width:0%;height:0px;',
                present_photoart_path:'width:0%;height:0px;',
            }

        }
    },

    beforeMount:function(){
    },
    mounted:function(){

    },
    created:async function(){

    },

    methods:{
        async save_movie(e,key){
            this.loading_input_key=key;

            await axios.post('/api/v1/g/patient/'+this.params.tbl_patient.tbl_patient_id+'/save_present'+'?api_token='+global.api_token,
                {
                    key:key,
                    file:e.target.files[0],
                },
                {
                    headers: {'content-type': 'multipart/form-data'},
                    onUploadProgress: (event) => {
                        let percent_completed = Math.round( (event.loaded * 100) / event.total );
                        this.loading_progress[key]='width:'+(percent_completed)+'%;height:10px;';
                    }
                }
            ).then((response) => {//リクエストの成功
                this.params.tbl_patient = response.data.result;
                let path =  this.params.tbl_patient[key];
                this.params.tbl_patient[key] = '';

                setTimeout(() => {
                    this.params.tbl_patient[key] = path;
                }, 1);

            }).catch((error) => {//リクエストの失敗
                alert('エラーが発生しました\nファイル拡張子、容量を確認してください');
            }).finally(() => {
                e.target.value='';
                this.loading_input_key='';
                this.loading_progress[key]='width:0%;';
            });
        }
    },
    watch:{
        // 'params.search_params.mst_material_groups.mst_material_group_id.in.0':function (e){
        //     if(e!=null){
        //         this.get_materials(this.params.search_params.mst_material_groups.mst_material_group_id.in[0]);
        //     }
        // },
    },
};
</script>

<style scoped>

</style>
