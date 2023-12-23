<template>
    <form class="em-filter" method="get" ref="form">
        {{params.tbl_patient.name}}
    </form><!--end em-filter-->



</template>

<script>

export default {
    name: "patients-edit",

    data(){
        return {
            params:params,
            shapes: {},
            material_groups: {},
            materials: {},
            suppliers: {},

            published_at_types: {
                0: '掲載中',
                1: '未掲載',
            },

            init_search_params: {
                tbl_patients:{
                    name: {
                        like: '',
                    },
                    is_use_instagram: {
                        like: '',
                    },
                    mst_maternity_id: {
                        in: [],
                    },
                    working_by: {
                        in: [],
                    },

                    created_at: {
                        from: '',
                        to: '',
                    },
                },



                // is_approval: '',
                // mst_shapes: {
                //     mst_shape_id: {
                //         in: [null],
                //     },
                // },
                // mst_material_groups: {
                //     mst_material_group_id: {
                //         in: [null],
                //     },
                // },
                // mst_materials: {
                //     mst_material_id: {
                //         in: [null],
                //     },
                // },
                //
                // tbl_supplier_product_versions: {
                //     approval_at: {
                //         isnull: null,
                //     },
                //     is_admin_edit: {
                //         in: [],
                //     },
                //     tbl_supplier_id: {
                //         in: [null],
                //     },
                //     is_volume_discount_settings_enabled: {
                //         in: [],
                //     },
                //     is_pre_order_discount_settings_enabled: {
                //         in: [],
                //     },
                //
                // },
                //
                // tbl_product_composition_registrations: {
                //     tbl_product_composition_id: {
                //         in: [],
                //     },
                //     published_at: {
                //         isnull: null,
                //     },
                //     created_at: {
                //         from: '',
                //         to: '',
                //     },
                // },
                // tbl_product_composition_registration_details: {
                //     name: {
                //         like: '',
                //     },
                // },
                // tbl_product_registrations: {
                //     mst_product_type_id: {
                //         in: [],
                //     },
                // },

            },

        }
    },

    beforeMount:function(){
    },
    mounted:function(){
        // if(this.params.search_params.mst_material_groups.mst_material_group_id.in[0]!=null){
        //     this.get_materials(this.params.search_params.mst_material_groups.mst_material_group_id.in[0]);
        // }
    },
    created:async function(){

    },

    methods:{

        async work_begin(tbl_patient_key){
            this.params.list[tbl_patient_key].is_highlight = true;
            if(window.confirm(this.params.list[tbl_patient_key].name+'さんの担当者として制作を開始します。')) {
                await axios.post('/api/v1/g/patient/'+this.params.list[tbl_patient_key].tbl_patient_id+'/work_begin'+'?api_token='+global.api_token,
                    {
                        tbl_patient_id:this.params.list[tbl_patient_key].tbl_patient_id,
                    }
                ).then((response) => {//リクエストの成功
                    this.params.list[tbl_patient_key] = response.data.result;
                }).catch((error) => {//リクエストの失敗
                    alert('エラーが発生しました\n正しく完了していない可能性があります。');
                }).finally(() => {
                    this.params.list[tbl_patient_key].is_highlight = false;
                });
            }else{
                this.params.list[tbl_patient_key].is_highlight = false;
            }
        },
        async work_complete(tbl_patient_key){
            this.params.list[tbl_patient_key].is_highlight = true;
            if(window.confirm(this.params.list[tbl_patient_key].name+'さんの作業を完了します。\nよろしいですか？')) {
                await axios.post('/api/v1/g/patient/'+this.params.list[tbl_patient_key].tbl_patient_id+'/work_complete'+'?api_token='+global.api_token,
                    {
                        tbl_patient_id:this.params.list[tbl_patient_key].tbl_patient_id,
                    }
                ).then((response) => {//リクエストの成功
                    this.params.list[tbl_patient_key] = response.data.result;
                }).catch((error) => {//リクエストの失敗
                    alert('エラーが発生しました\n正しく完了していない可能性があります。');
                }).finally(() => {
                    this.params.list[tbl_patient_key].is_highlight = false;
                });
            }else{
                this.params.list[tbl_patient_key].is_highlight = false;
            }
        },
        page_link_click:function(page){
            let t = this;
            this.params.search_params.page = page;
            setTimeout(() => {t.$refs.form.submit();}, 5);
        },
        submit:function(){
            let t = this;
            this.params.search_params.page = 1;
            setTimeout(() => {t.$refs.form.submit();}, 5);
        },
        text_copy:function(txt){
            navigator.clipboard.writeText(txt);
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
