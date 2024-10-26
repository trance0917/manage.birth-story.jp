<template>
    <form class="em-filter" method="get" ref="form">
        <dl class="em-filter-box">

            <div class="em-filter-box-item">
                <dt class="w-19">ママの名前:</dt>
                <dd><input class="em-input-small w-34" type="text" name="tbl_patients[name][like]" v-model="params.search_params.tbl_patients.name.like" /></dd>
            </div>

            <div class="em-filter-box-item">
                <dt class="w-19">インスタ:</dt>
                <dd><select class="w-24 em-input-small" name="tbl_patients[is_use_instagram][like]" v-model="params.search_params.tbl_patients.is_use_instagram.like">
                    <option value="">--</option>
                    <option v-for="(is_use_instagram,is_use_instagram_key) in global.is_use_instagrams" :value="is_use_instagram_key">{{is_use_instagram}}</option>
                </select></dd>
            </div>
            <div class="em-filter-box-item">
                <dt class="w-19">抽出:</dt>
                <ul class="flex gap-0.5 max-w-[150px] flex-wrap">
                    <li><input type="checkbox" id="submitted_at" true-value="1" value="1" name="tbl_patients[submitted_at][isnotnull]" v-model="params.search_params.tbl_patients.submitted_at.isnotnull" /><label for="submitted_at">申込済</label></li>
                    <li><input type="checkbox" id="undertook_at" true-value="1" value="1" name="tbl_patients[undertook_at][isnotnull]" v-model="params.search_params.tbl_patients.undertook_at.isnotnull" /><label for="undertook_at">作業開始</label></li>
                    <li><input type="checkbox" id="completed_at" true-value="1" value="1" name="tbl_patients[completed_at][isnotnull]" v-model="params.search_params.tbl_patients.completed_at.isnotnull" /><label for="completed_at">完了</label></li>
                    <li><input type="checkbox" id="reviewed_at" true-value="1" value="1" name="tbl_patients[reviewed_at][isnotnull]" v-model="params.search_params.tbl_patients.reviewed_at.isnotnull" /><label for="reviewed_at">アンケ済</label></li>
                    <li><input type="checkbox" id="is_google_review" true-value="1" value="1" name="tbl_patients[is_google_review][in][]" v-model="params.search_params.tbl_patients.is_google_review.in[0]" /><label for="is_google_review">GR確認</label></li>
                </ul>
            </div>

        </dl><!--end em-filter-box-->
        <dl class="em-filter-box">
            <div class="em-filter-box-item">
                <dt class="w-[50px]">産院:</dt>
                <dd><select class="em-input-small w-40" name="tbl_patients[mst_maternity_id][in][]" v-model="params.search_params.tbl_patients.mst_maternity_id.in[0]">
                    <option value="">--</option>
                    <option v-for="(maternity,maternity_key) in params.mst_maternities" :value="maternity.mst_maternity_id">{{maternity.name}}</option>
                </select></dd>
            </div>
            <div class="em-filter-box-item">
                <dt class="w-[50px]">担当者:</dt>
                <dd><select class="em-input-small w-40" name="tbl_patients[working_by][in][]" v-model="params.search_params.tbl_patients.working_by.in[0]">
                    <option value="">--</option>
                    <option v-for="(user,user_key) in params.users" :value="user.tbl_user_id">{{user.name}}</option>
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
                <dt class="w-14">出産日:</dt>
                <dd><input class="em-input-small w-28" type="date" name="tbl_patients[birth_day][date_from]" :max="params.search_params.tbl_patients.birth_day.date_to" v-model="params.search_params.tbl_patients.birth_day.date_from" />
                    ～
                    <input class="em-input-small w-28" type="date" name="tbl_patients[birth_day][date_to]" :min="params.search_params.tbl_patients.birth_day.date_from" v-model="params.search_params.tbl_patients.birth_day.date_to" /></dd>
            </div>
            <div class="em-filter-box-item">
                <dt class="w-14">健診日:</dt>
                <dd><input class="em-input-small w-28" type="date" name="tbl_patients[health_check_date][date_from]" :max="params.search_params.tbl_patients.health_check_date.date_to" v-model="params.search_params.tbl_patients.health_check_date.date_from" />
                    ～
                    <input class="em-input-small w-28" type="date" name="tbl_patients[health_check_date][date_to]" :min="params.search_params.tbl_patients.health_check_date.date_from" v-model="params.search_params.tbl_patients.health_check_date.date_to" /></dd>
            </div>
        </dl><!--end em-filter-box-->

        <dl class="em-filter-box">
            <div class="em-filter-box-item">
                <dt class="w-14">更新日:</dt>
                <dd><input class="em-input-small w-28" type="date" name="tbl_patients[updated_at][date_from]" :max="params.search_params.tbl_patients.updated_at.date_to" v-model="params.search_params.tbl_patients.updated_at.date_from" />
                    ～
                    <input class="em-input-small w-28" type="date" name="tbl_patients[updated_at][date_to]" :min="params.search_params.tbl_patients.updated_at.date_from" v-model="params.search_params.tbl_patients.updated_at.date_to" /></dd>
            </div>
            <div class="em-filter-box-item">
                <dt class="w-14">登録日:</dt>
                <dd><input class="em-input-small w-28" type="date" name="tbl_patients[created_at][date_from]" :max="params.search_params.tbl_patients.created_at.date_to" v-model="params.search_params.tbl_patients.created_at.date_from" />
                    ～
                    <input class="em-input-small w-28" type="date" name="tbl_patients[created_at][date_to]" :min="params.search_params.tbl_patients.created_at.date_from" v-model="params.search_params.tbl_patients.created_at.date_to" /></dd>
            </div>
        </dl><!--end em-filter-box-->

        <dl class="em-filter-box flex items-end">
            <div class="em-filter-box-item">
                <button class="em-btn px-7 py-1.5 bg-orange" type="submit" @click="submit">検索</button>
            </div>
        </dl><!--end em-filter-box-->
        <input type="hidden" name="page" v-model="params.search_params.page" />
        <input type="hidden" name="per" v-model="params.search_params.per" />
        <input type="hidden" name="sort_key" v-model="params.search_params.sort_key" />
        <input type="hidden" name="sort_type" v-model="params.search_params.sort_type" />
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
                <th class="w-[55px] cursor-pointer !bg-orange-100 hover:!bg-orange-200 relative" @click="sort_change('tbl_patient_id',params.search_params.sort_type)">
                    #
                    <i v-if="params.search_params.sort_key!='tbl_patient_id'" class="fa-solid fa-sort"></i>
                    <i v-else-if="params.search_params.sort_key!='tbl_patient_id' || params.search_params.sort_type!='desc'" class="fa-solid fa-sort-up"></i>
                    <i v-else-if="params.search_params.sort_key!='tbl_patient_id' || params.search_params.sort_type!='asc'" class="fa-solid fa-sort-down"></i>
                </th>

                <th class="w-[100px]">状態</th>
                <th class="w-[54px]"><i class="fa-solid fa-gift"></i></th>

                <th class="w-[180px]">ママの名前</th>
                <th class="w-[68px] cursor-pointer !bg-orange-100 hover:!bg-orange-200 relative" @click="sort_change('birth_day',params.search_params.sort_type)">
                    出産日
                    <i v-if="params.search_params.sort_key!='birth_day'" class="fa-solid fa-sort"></i>
                    <i v-else-if="params.search_params.sort_key!='birth_day' || params.search_params.sort_type!='desc'" class="fa-solid fa-sort-up"></i>
                    <i v-else-if="params.search_params.sort_key!='birth_day' || params.search_params.sort_type!='asc'" class="fa-solid fa-sort-down"></i>
                </th>
                <th class="w-[68px] cursor-pointer !bg-orange-100 hover:!bg-orange-200 relative" @click="sort_change('health_check_date',params.search_params.sort_type)">
                    健診日
                    <i v-if="params.search_params.sort_key!='health_check_date'" class="fa-solid fa-sort"></i>
                    <i v-else-if="params.search_params.sort_key!='health_check_date' || params.search_params.sort_type!='desc'" class="fa-solid fa-sort-up"></i>
                    <i v-else-if="params.search_params.sort_key!='health_check_date' || params.search_params.sort_type!='asc'" class="fa-solid fa-sort-down"></i>
                </th>

                <th class="w-[80px]">コード</th>
                <th class="w-[34px]"><i class="fa-solid fa-copy"></i></th>
                <th class="w-[100px]">産院</th>

                <th class="w-[64px]">インスタ</th>
                <th class="w-[54px]">review</th>

                <th class="w-[92px]">申込完了日時</th>
                <th class="w-[92px]">LINE名</th>
<!--                <th class="w-[64px]">ポイント</th>-->
<!--                <th class="w-[56px]">支払</th>-->
                <th class="w-[92px]">作業開始日時</th>
                <th class="w-[90px]">担当者</th>
                <th class="w-[92px]">作業完了日時</th>
                <th class="w-[102px]">プレゼント日時</th>

                <th class="w-[230px]">進捗</th>

                <th class="w-[86px] cursor-pointer !bg-orange-100 hover:!bg-orange-200 relative" @click="sort_change('created_at',params.search_params.sort_type)">
                    登録日
                    <i v-if="params.search_params.sort_key!='created_at'" class="fa-solid fa-sort"></i>
                    <i v-else-if="params.search_params.sort_key!='created_at' || params.search_params.sort_type!='desc'" class="fa-solid fa-sort-up"></i>
                    <i v-else-if="params.search_params.sort_key!='created_at' || params.search_params.sort_type!='asc'" class="fa-solid fa-sort-down"></i>

                </th>
                <th class="w-[86px] cursor-pointer !bg-orange-100 hover:!bg-orange-200 relative" @click="sort_change('updated_at',params.search_params.sort_type)">
                更新日
                    <i v-if="params.search_params.sort_key!='updated_at'" class="fa-solid fa-sort"></i>
                    <i v-else-if="params.search_params.sort_key!='updated_at' || params.search_params.sort_type!='desc'" class="fa-solid fa-sort-up"></i>
                    <i v-else-if="params.search_params.sort_key!='updated_at' || params.search_params.sort_type!='asc'" class="fa-solid fa-sort-down"></i>
                </th>
                <th class="w-[40px]">--</th>
            </tr>

            <template v-if="params.list.length">
                <template v-for="(tbl_patient ,tbl_patient_key) in params.list" :class="{'bg-orange-100':tbl_patient.is_highlight}">
                    <tr :class="{'opacity-30':tbl_patient.deleted_at,'bg-slate-150':tbl_patient.undertook_at}">
                        <!--#-->
                        <td class="w-[55px]"><a class="text-main hover:underline" :href="'/patients/'+tbl_patient.tbl_patient_id">{{ ('0000'+tbl_patient.tbl_patient_id).slice(-5) }}</a></td>
                        <!--状態-->
                        <td class="w-[100px] justify-center relative">
                            <ul class="space-x-[5px] flex">
                                <template v-if="tbl_patient.completed_at">
                                    <li><span class="bg-green-400 text-white p-[1px_4px]">完成</span></li>
                                </template>
                                <template v-else-if="!tbl_patient.submitted_at">
                                    <li><span class="bg-slate-300 text-white p-[1px_4px]">申込待ち</span></li>
                                </template>

                                <template v-else-if="tbl_patient.submitted_at">
                                    <template v-if="!tbl_patient.working_by">
                                        <li><span class="text-main underline font-bold cursor-pointer" @click="work_begin(tbl_patient_key)">作業開始</span></li>
                                    </template>
                                    <template v-else>
                                        <template v-if="tbl_patient.working_by==global.user.tbl_user_id">
                                            <li class="mr-[7px]"><a class="text-main underline font-bold" :href="global.front_app_basic_url+'/dl/'+tbl_patient.code" target="_blank">DL</a></li>
                                        </template>
                                        <li><span class="text-red underline font-bold cursor-pointer" @click="work_complete(tbl_patient_key)">完了</span></li>
                                    </template>
                                </template>

                                <template v-if="tbl_patient.payment_status==2">
                                    <li class="text-[10px]"><span class="font-bold text-red underline text-bold cursor-pointer" @click="payment_complete(tbl_patient_key)">支払</span></li>
                                </template>
                            </ul>
                            <i v-if="tbl_patient.presented_at" class="absolute text-red right-[2px] bottom-[2px] text-[8px] fa-solid fa-gift"></i>
                        </td>

                        <!--fa-gift-->
                        <td class="w-[54px] flex justify-center">
                            <ul class="flex space-x-[5px]">
                                <li><i class="fa-regular fa-image text-slate-250" :class="{'!text-green-300':tbl_patient.present_movie_path}"></i></li>
                                <li><i class="fa-brands fa-youtube text-slate-250" :class="{'!text-green-300':tbl_patient.present_photoart_path}"></i></li>
                            </ul>
                        </td>

                        <!--ママの名前-->
                        <td class="w-[180px]  relative" :tooltip-data="tbl_patient.undertook_at">
                            <span class="truncate">
                                <template v-if="tbl_patient.name">{{ tbl_patient.name }}</template><template v-else>--</template>
                                (<template v-if="tbl_patient.roman_alphabet">{{ tbl_patient.roman_alphabet }}</template><template v-else>--</template>)
                            </span>

                            <i v-if="tbl_patient.message" class="absolute text-red right-[2px] bottom-[2px] text-[10px] fa-regular fa-comment-dots"></i>
                        </td>

                        <!--出産日-->
                        <td class="w-[68px]">
                            <span class="tool-tip" :tooltip-data="tbl_patient.birth_day">{{ short_day(tbl_patient.birth_day) }}</span>
                        </td>

                        <!--健診日-->
                        <td class="w-[68px]">
                            <span class="tool-tip" :tooltip-data="tbl_patient.health_check_date">{{ short_day(tbl_patient.health_check_date) }}</span>
                        </td>

                        <!--コード-->
                        <td class="w-[80px]"><a class="text-main hover:underline" :href="global.front_url+'/'+tbl_patient.code" target="_blank">{{ tbl_patient.code }}</a></td>

                        <!--fa-copy-->
                        <td class="w-[34px] flex justify-center">
                            <i class="absolute fa-regular fa-copy text-slate-600 hover:cursor-pointer hover:text-slate-400 active:text-white active:bg-green p-[5px_9px] rounded-sm"
                               @click="text_copy(tbl_patient_key)"
                            ></i>
                        </td>

                        <!--産院-->
                        <td class="w-[100px]"><span class="truncate">{{ tbl_patient.mst_maternity.name }}</span></td>

                        <!--インスタ-->
                        <td class="w-[64px] justify-center" :class="{'font-bold text-green':tbl_patient.is_use_instagram==1,'font-bold text-slate-400':tbl_patient.is_use_instagram==2}">
                            <template v-if="tbl_patient.is_use_instagram==1">許可</template>
                            <template v-else-if="tbl_patient.is_use_instagram==2">不許可</template>
                            <template v-else>--</template>
                        </td>

                        <!--review-->
                        <td class="w-[54px] justify-center relative" >
                            <ul class="space-x-[3px] flex">
                                <li><i class="fa-solid fa-star text-slate-250 text-[10px]" :class="{'text-star':tbl_patient.reviewed_at}"></i></li>
                                <li :class="{'hidden':!tbl_patient.reviewed_at}">
                                    <i class="!inline text-[10px] fa-brands fa-google p-[2px_3px_3px] rounded border cursor-pointer"
                                        :class="{
                                            'text-white bg-google border-google':tbl_patient.is_google_review,
                                            'text-slate-350 hover:bg-slate-200 border-slate-300':!tbl_patient.is_google_review
                                        }"
                                       @click="change_is_google_review(tbl_patient_key)">
                                    </i>
                                </li>
                            </ul>
                            <i v-if="tbl_patient.present_after_notified_at" class="absolute text-red right-[2px] bottom-[2px] text-[8px] fa-solid fa-volume-high"></i>
                        </td>



                        <!--申込完了日時-->
                        <td class="w-[92px]">
                            <span class="tool-tip" :tooltip-data="tbl_patient.submitted_at">{{ short_date(tbl_patient.submitted_at) }}</span>
                        </td>

                        <!--LINE名-->
                        <td class="w-[92px]">
                            <span class="truncate">
                            <template v-if="tbl_patient.line_name">{{ tbl_patient.line_name }}</template>
                            <template v-else>--</template>
                            </span>
                        </td>

<!--                        <td class="w-[64px] justify-end">{{ tbl_patient.review_point }}</td>-->

<!--                        <td class="w-[56px] justify-center" :class="{'font-bold text-red':tbl_patient.payment_status==2,'font-bold text-green':tbl_patient.payment_status==3,'font-bold text-slate-400':tbl_patient.payment_status==4,}">-->
<!--                            <span class="tool-tip" v-if="tbl_patient.payment_status==3" :tooltip-data="'支払者：'+(tbl_patient.tbl_user_payment_by!==null?tbl_patient.tbl_user_payment_by.name:'&#45;&#45;')">{{ global.payment_statuses[tbl_patient.payment_status] }}</span>-->
<!--                            <template v-else>{{ global.payment_statuses[tbl_patient.payment_status] }}</template>-->
<!--                        </td>-->

                        <!--作業開始日時-->
                        <td class="w-[92px]">
                            <span class="tool-tip" :tooltip-data="tbl_patient.undertook_at">{{ short_date(tbl_patient.undertook_at) }}</span>
                        </td>

                        <!--担当者-->
                        <td class="w-[90px] !p-0"
                            v-if="tbl_patient.submitted_at">
                            <select class="em-input-small w-40 !border-0 !text-[12px] !bg-transparent" v-model="tbl_patient.working_by" @change="change_working_by($event,tbl_patient_key)" :disabled="tbl_patient.completed_at">
                                <option v-for="(user,user_key) in params.users" :value="user.tbl_user_id">{{user.name}}</option>
                            </select>
                        </td>
                        <td class="w-[90px]" v-else>--</td>


                        <!--作業完了日時-->
                        <td class="w-[92px]">
                            <span class="tool-tip" :tooltip-data="tbl_patient.completed_at">{{ short_date(tbl_patient.completed_at) }}</span>
                        </td>

                        <!--プレゼント日時-->
                        <td class="w-[102px]">
                            <span class="tool-tip" :tooltip-data="tbl_patient.presented_at">{{ short_date(tbl_patient.presented_at) }}</span>
                        </td>

                        <!--進捗-->
                        <td class="w-[230px] justify-center">
                            <ul class="flex space-x-[3px]">
                                <li v-if="!tbl_patient.task_retouch_by"><span class="cursor-pointer p-[1px_3px] bg-slate-300 text-white tool-tip hover:bg-slate-400" tooltip-data="写真レタッチ" @click="task_retouch_by_complete(tbl_patient_key)">補正</span></li>
                                <li v-else><span class="p-[1px_3px] bg-green-400 text-white tool-tip" :tooltip-data="'写真レタッチ：'+tbl_patient.tbl_user_task_retouch_by.name">補正</span></li>
                                <li><span class="cursor-pointer p-[1px_3px] bg-slate-300 text-white tool-tip hover:bg-slate-400" tooltip-data="フォト">フォ</span></li>
                                <li><span class="cursor-pointer p-[1px_3px] bg-slate-300 text-white tool-tip hover:bg-slate-400" tooltip-data="ムービーDVD">DVD</span></li>
                                <li><span class="cursor-pointer p-[1px_3px] bg-slate-300 text-white tool-tip hover:bg-slate-400" tooltip-data="MP4データ">MP4</span></li>
                                <li><span class="cursor-pointer p-[1px_3px] bg-slate-300 text-white tool-tip hover:bg-slate-400" tooltip-data="オーサリング">オサ</span></li>
                                <li><span class="cursor-pointer p-[1px_3px] bg-slate-300 text-white tool-tip hover:bg-slate-400" tooltip-data="最終チェック">確認</span></li>
                            </ul>
                        </td>

                        <!--登録日-->
                        <td class="w-[86px]">
                            <span class="tool-tip" :tooltip-data="tbl_patient.created_at">{{ short_date(tbl_patient.created_at) }}</span>
                        </td>

                        <!--更新日-->
                        <td class="w-[86px]">
                            <span class="tool-tip" :tooltip-data="tbl_patient.updated_at">{{ short_date(tbl_patient.updated_at) }}</span>
                        </td>

                        <!------>
                        <td class="w-[40px]">
                            <a class="text-[10px] hover:underline" href="javascript:void(0);" @click="change_deleted_at(tbl_patient_key)">削除</a>
                        </td>
                    </tr>
                </template>
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
                        date_from: '',
                        date_to: '',
                    },
                    updated_at: {
                        date_from: '',
                        date_to: '',
                    },
                    health_check_date: {
                        date_from: '',
                        date_to: '',
                    },
                    birth_day: {
                        date_from: '',
                        date_to: '',
                    },
                    submitted_at: {
                        isnotnull: null,
                    },
                    undertook_at: {
                        isnotnull: null,
                    },
                    completed_at: {
                        isnotnull: null,
                    },

                    reviewed_at: {
                        isnotnull: null,
                    },
                    is_google_review: {
                        in: [],
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
        async payment_complete(tbl_patient_key){
            this.params.list[tbl_patient_key].is_highlight = true;
            if(window.confirm(this.params.list[tbl_patient_key].name+'さんの支払いを完了します。\nよろしいですか？')) {
                await axios.post('/api/v1/g/patient/'+this.params.list[tbl_patient_key].tbl_patient_id+'/payment_complete'+'?api_token='+global.api_token,
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
        async change_working_by(e,tbl_patient_key){
            let user = {};
            for(let user_key in this.params.users){
                user = this.params.users[user_key];
                if(e.target.value == user.tbl_user_id)break;
            }

            this.params.list[tbl_patient_key].is_highlight = true;

            if(window.confirm(this.params.list[tbl_patient_key].name+'さんの作業担当を'+user.name+'さんに渡します。\nよろしいですか？')) {
                await axios.post('/api/v1/g/patient/'+this.params.list[tbl_patient_key].tbl_patient_id+'/change_working_by'+'?api_token='+global.api_token,
                    {
                        tbl_patient_id:this.params.list[tbl_patient_key].tbl_patient_id,
                        working_by:user.tbl_user_id,
                    }
                ).then((response) => {//リクエストの成功
                    this.params.list[tbl_patient_key] = response.data.result;
                }).catch((error) => {//リクエストの失敗
                    alert('エラーが発生しました\n正しく完了していない可能性があります。');
                }).finally(() => {
                    this.params.list[tbl_patient_key].is_highlight = false;
                });
            }else{
                this.params.list[tbl_patient_key].working_by = this.params.list[tbl_patient_key].old_working_by;
                this.params.list[tbl_patient_key].is_highlight = false;
            }
        },

        async task_retouch_by_complete(tbl_patient_key){
            this.params.list[tbl_patient_key].is_highlight = true;
            if(window.confirm(this.params.list[tbl_patient_key].name+'さんのレタッチを完了します。\nよろしいですか？')) {
                await axios.post('/api/v1/g/patient/'+this.params.list[tbl_patient_key].tbl_patient_id+'/task_retouch_by_complete'+'?api_token='+global.api_token,
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



        async change_deleted_at(tbl_patient_key){
                this.params.list[tbl_patient_key].is_highlight = true;
            if(window.confirm(this.params.list[tbl_patient_key].name+'さんのデータを削除します。\nよろしいですか？')) {
                await axios.post('/api/v1/g/patient/'+this.params.list[tbl_patient_key].tbl_patient_id+'/change_deleted_at'+'?api_token='+global.api_token,
                    {
                        tbl_patient_id:this.params.list[tbl_patient_key].tbl_patient_id,
                    }
                ).then((response) => {//リクエストの成功
                    this.params.list[tbl_patient_key].deleted_at = response.data.result.deleted_at;
                }).catch((error) => {//リクエストの失敗
                    alert('エラーが発生しました\n正しく完了していない可能性があります。');
                }).finally(() => {
                    this.params.list[tbl_patient_key].is_highlight = false;
                });
            }else{
                this.params.list[tbl_patient_key].is_highlight = false;
            }
        },

        async change_is_google_review(tbl_patient_key){
            this.params.list[tbl_patient_key].is_highlight = true;
            let txt = '';
            if(!this.params.list[tbl_patient_key].is_google_review){
                txt = this.params.list[tbl_patient_key].name+'さんのGoogleレビューを確認しました。\nチェックしますか？';
            }else{
                txt = this.params.list[tbl_patient_key].name+'さんのGoogleレビューを取り消します。\nよろしいですか？';
            }

            if(window.confirm(txt)) {
                await axios.post('/api/v1/g/patient/'+this.params.list[tbl_patient_key].tbl_patient_id+'/change_is_google_review'+'?api_token='+global.api_token,
                    {
                        tbl_patient_id:this.params.list[tbl_patient_key].tbl_patient_id,
                        is_google_review:this.params.list[tbl_patient_key].is_google_review
                    }
                ).then((response) => {//リクエストの成功
                    this.params.list[tbl_patient_key].is_google_review = response.data.result.is_google_review;
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
        // text_copy:function(txt){
        //     navigator.clipboard.writeText(txt);
        // },
        short_date:function(date_str){
            if(!date_str){
                return '--';
            }
            let date = new Date(date_str);
            let year = date.getFullYear();
            let month = (date.getMonth()+1).toString().padStart(2, '0');
            let day = date.getDate().toString().padStart(2, '0');
            let hour = date.getHours().toString().padStart(2, '0');
            let minute = date.getMinutes().toString().padStart(2, '0');
            return month+'-'+day+' '+''+hour+':'+minute;
        },
        short_day:function(day_str){
            if(!day_str){
                return '--';
            }
            let date = new Date(day_str);
            let year = ("" + date.getFullYear()).slice(-2);
            let month = (date.getMonth()+1).toString().padStart(2, '0');
            let day = date.getDate().toString().padStart(2, '0');
            return year+'-'+month+'-'+day;
        },

        text_copy:function(tbl_patient_key){
            let tbl_patient = this.params.list[tbl_patient_key];
            let txt = '';

            let code = tbl_patient.code;
            let name = tbl_patient.name ? tbl_patient.name:'';
            let roman_alphabet = tbl_patient.roman_alphabet ? tbl_patient.roman_alphabet : '';
            let baby_roman_alphabet = tbl_patient.baby_roman_alphabet ? tbl_patient.baby_roman_alphabet : '';
            let birth_day = tbl_patient.birth_day ? tbl_patient.birth_day : '';
            let birth_time = tbl_patient.birth_time ? tbl_patient.birth_time : '';

            let weight = tbl_patient.weight ? tbl_patient.weight : '';
            let height = tbl_patient.height ? tbl_patient.height : '';

            let health_check_date = tbl_patient.health_check_date ? tbl_patient.health_check_date : '';

            let use_instagram = '';
            if(tbl_patient.is_use_instagram=='1'){
                use_instagram = '〇';
            }else{
                use_instagram = '×';
            }
            txt = code + '\t' + name + '\t' + roman_alphabet + '\t' + '\t' + baby_roman_alphabet + '\t' + birth_day + '\t' + birth_time + '\t' + weight + '\t' + height + '\t' + health_check_date + '\t' + use_instagram;
            navigator.clipboard.writeText(txt).then(
                () => {
                    // コピーに成功したときの処理
                },
                () => {
                    // コピーに失敗したときの処理
                });

        },
        sort_change(sort_key,sort_type){
            if(this.params.search_params.sort_key != sort_key){
                this.params.search_params.sort_type='desc';
            }else{
                if(sort_type == 'asc'){
                    this.params.search_params.sort_type = 'desc';
                }else{
                    this.params.search_params.sort_type = 'asc';
                }
            }
            this.params.search_params.sort_key=sort_key;

            this.submit();
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
