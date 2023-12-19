<template>
    <form class="em-filter" method="get" ref="form">
        <dl class="em-filter-box">

            <div class="em-filter-box-item">
                <dt class="w-19">ママの名前:</dt>
                <dd><input class="em-input-small w-34" type="text" name="tbl_patients[name][like]" v-model="params.search_params.tbl_patients.name.like" /></dd>
            </div>

<!--            <div class="em-filter-box-item">-->
<!--                <dt class="w-19">掲載状態:</dt>-->
<!--                <dd><select class="w-24 em-input-small" v-model="params.search_params.tbl_product_composition_registrations.published_at.isnull">-->
<!--                    <option :value="null">&#45;&#45;</option>-->
<!--                    <option v-for="(published_at_type,published_at_type_key) in published_at_types" :value="published_at_type_key">{{published_at_type}}</option>-->
<!--                </select></dd>-->
<!--            </div>-->

        </dl><!--end em-filter-box-->
        <dl class="em-filter-box">
            <div class="em-filter-box-item">
                <dt class="w-[50px]">産院:</dt>
                <dd><select class="em-input-small w-40" name="tbl_patients[mst_maternity_id][in][]" v-model="params.search_params.tbl_patients.mst_maternity_id.in[0]">
                    <option value="">--</option>
                    <option v-for="(maternity,maternity_key) in params.mst_maternities" :value="maternity.mst_maternity_id">{{maternity.name}}</option>
                </select></dd>
            </div>
<!--            <div class="em-filter-box-item">-->
<!--                <dt class="w-16">割引:</dt>-->
<!--                <ul class="flex space-x-0.5">-->
<!--                    <li><input type="checkbox" id="discount-settings-1" true-value="1" value="1" v-model="params.search_params.tbl_supplier_product_versions.is_pre_order_discount_settings_enabled.in[0]" /><label for="discount-settings-1">予注</label></li>-->
<!--                    <li><input type="checkbox" id="discount-settings-2" true-value="1" value="1" v-model="params.search_params.tbl_supplier_product_versions.is_volume_discount_settings_enabled.in[0]" /><label for="discount-settings-2">スラ</label></li>-->
<!--                </ul>-->
<!--            </div>-->
<!--            <div class="em-filter-box-item">-->
<!--                <dt class="w-16">編集状態:</dt>-->
<!--                <ul class="flex space-x-0.5">-->
<!--                    <li><input type="checkbox" id="is_approval" true-value="1" value="1" v-model="params.search_params.is_approval" /><label for="is_approval">承諾待ち</label></li>-->
<!--                </ul>-->
<!--            </div>-->
        </dl><!--end em-filter-box-->

        <dl class="em-filter-box">
            <div class="em-filter-box-item">
                <dt class="w-22">登録日:</dt>
                <dd><input class="em-input-small w-28" type="date" :max="params.search_params.tbl_product_composition_registrations.created_at.to" v-model="params.search_params.tbl_product_composition_registrations.created_at.from" />
                    ～
                    <input class="em-input-small w-28" type="date" :min="params.search_params.tbl_product_composition_registrations.created_at.from" v-model="params.search_params.tbl_product_composition_registrations.created_at.to" /></dd>
            </div>
        </dl><!--end em-filter-box-->
        <dl class="em-filter-box flex items-end">
            <div class="em-filter-box-item">
                <button class="em-btn px-7 py-1.5 bg-orange" type="submit" @click="submit">検索</button>
            </div>
        </dl><!--end em-filter-box-->
        <input type="hidden" name="page" v-model="params.search_params.page" />
        <input type="hidden" name="per" v-model="params.search_params.per" />
    </form><!--end em-filter-->


    <div class="flex items-center mb-[10px]">
        <pagination :pagination="params.pagination" @page_link_click="page_link_click"></pagination>
        <select class="h-7.5 ml-2 w-16" v-model="params.search_params.per" @change="submit">
            <option value="5">5</option>
            <option value="20">20</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>
    </div>




    <div class="em-list">
        <table>
            <tbody>
            <tr>
                <th class="w-[55px]">#</th>
                <th class="w-[34px]">DL</th>
                <th class="w-[90px]">コード</th>
                <th class="w-[180px]">産院</th>
                <th class="w-[110px]">ママの名前</th>
                <th class="w-[100px]">インスタ</th>
                <th class="w-[100px]">DL済</th>
                <th class="w-[100px]">出産日</th>
                <th class="w-[100px]">健診予定日</th>
                <th class="w-[114px]">申込完了日時</th>
                <th class="w-[100px]">ポイント</th>
                <th class="w-[100px]">支払状態</th>
                <th class="w-[114px]">対応開始日時</th>
                <th class="w-[100px]">対応者</th>
                <th class="w-[114px]">プレゼント日</th>
                <th class="w-[114px]">登録日</th>
                <th class="w-[114px]">更新日</th>
                <th class="w-[140px]">リッチメニュー</th>
            </tr>

            <template v-if="params.list.length">
                <tr v-for="(tbl_patient ,tbl_patient_key) in params.list">
                    <td class="w-[55px]"><a class="text-main hover:underline" :href="'/patiens/'+tbl_patient.tbl_patient_id">{{ ('0000'+tbl_patient.tbl_patient_id).slice(-5) }}</a></td>
                    <td class="w-[34px]"><a class="text-main hover:underline" :href="'/patiens/'+tbl_patient.tbl_patient_id+'/dl'">DL</a></td>
                    <td class="w-[90px]"><a class="text-main hover:underline" :href="global.front_url+'/'+tbl_patient.code" target="_blank">{{ tbl_patient.code }}</a></td>
                    <td class="w-[180px] "><span class="truncate">{{ tbl_patient.mst_maternity.name }}</span></td>
                    <td class="w-[110px]"><span class="truncate"><template v-if="tbl_patient.name">{{ tbl_patient.name }}</template><template v-else>--</template></span></td>
                    <td class="w-[114px]">{{ tbl_patient.created_at }}</td>
                    <td class="w-[114px]">{{ tbl_patient.updated_at }}</td>
                    <td class="w-[140px] cursor-pointer hover:bg-main/10" @click="text_copy(tbl_patient.richmenu_id)"><span class="truncate">{{ tbl_patient.richmenu_id }}</span></td>
                    <td class="w-[114px]">{{ tbl_patient.richmenu_id }}</td>
                </tr>
            </template>
            <tr v-else>
                <td colspan="100" class="p-5 w-full">該当データなし</td>
            </tr>
            </tbody>

        </table>
    </div><!--end em-list-->

    <div class="mt-[10px]"><pagination :pagination="params.pagination" @page_link_click="page_link_click"></pagination></div>

</template>

<script>
import Pagination from "@/components/common/Pagination.vue";

export default {
    name: "app",

    //子コンポーネント
    components: {
        pagination:Pagination,
    },

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
                    mst_maternity_id: {
                        in: [null],
                    },
                },



                is_approval: '',
                mst_shapes: {
                    mst_shape_id: {
                        in: [null],
                    },
                },
                mst_material_groups: {
                    mst_material_group_id: {
                        in: [null],
                    },
                },
                mst_materials: {
                    mst_material_id: {
                        in: [null],
                    },
                },

                tbl_supplier_product_versions: {
                    approval_at: {
                        isnull: null,
                    },
                    is_admin_edit: {
                        in: [],
                    },
                    tbl_supplier_id: {
                        in: [null],
                    },
                    is_volume_discount_settings_enabled: {
                        in: [],
                    },
                    is_pre_order_discount_settings_enabled: {
                        in: [],
                    },

                },

                tbl_product_composition_registrations: {
                    tbl_product_composition_id: {
                        in: [],
                    },
                    published_at: {
                        isnull: null,
                    },
                    created_at: {
                        from: '',
                        to: '',
                    },
                },
                tbl_product_composition_registration_details: {
                    name: {
                        like: '',
                    },
                },
                tbl_product_registrations: {
                    mst_product_type_id: {
                        in: [],
                    },
                },

            },

        }
    },

    beforeMount:function(){
            this.params.search_params = this.merge_deeply(this.init_search_params,this.params.search_params);
    },
    mounted:function(){
        // if(this.params.search_params.mst_material_groups.mst_material_group_id.in[0]!=null){
        //     this.get_materials(this.params.search_params.mst_material_groups.mst_material_group_id.in[0]);
        // }
    },
    created:async function(){

    },

    methods:{
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
