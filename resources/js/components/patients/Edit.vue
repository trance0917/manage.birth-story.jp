<template>
    <div class="space-y-[15px]">
        <div v-if="is_show_save_msg" class="inline-block p-[10px] bg-green">
            <p class="text-white font-bold">保存しました。</p>
        </div>

        <div v-if="is_show_save_error_msg" class="inline-block p-[10px] bg-red">
            <p class="text-white font-bold">エラーが発生しました。保存できていない可能性があります。</p>
        </div>

        <div class="em-board">
            <div class="em-board-content space-y-[15px]">
                <div class="em-input-box">
                    <p class="em-input-head">備考</p>
                    <div class="flex items-end space-x-[10px]">
                        <textarea class="w-[500px] h-[150px] text-[16px] leading-normal" cols="80" rows="4" v-model="params.tbl_patient.memo"></textarea>
                        <button class="em-btn px-[20px] py-[4px] bg-orange" type="submit" @click="save_memo">保存</button>
                    </div>
                </div>
                <div class="em-input-box">
                    <p class="em-input-head">プレゼント</p>

                    <div class="p-[20px] bg-green-50 space-y-[15px]">
                        <div class="flex space-x-[20px]">
                            <div class="em-input-box">
                                <p class="em-input-head">プレゼントの動画</p>
                                <div class="w-[500px]" @dragover.prevent @drop="handle_drop($event,'present_movie_path')">
                                    <label class="relative" for="present_movie_path" v-if="!params.tbl_patient.present_movie_path">
                                        <div class="aspect-video text-slate-400 text-[16px] bg-slate-100 block text-center border border-dashed border-slate-300 flex items-center justify-center hover:bg-slate-150 cursor-pointer leading-snug"><div><span class="font-bold">動画を設定</span><br /><span class="text-[13px]">拡張子:mp4 / サイズ:400MB以下</span><span class="font-bold text-slate-250 text-[26px] mt-[10px] block">ドラッグ可</span></div></div>
                                        <i v-if="'present_movie_path'==loading_input_key"
                                           class="fa-solid fa-spinner fa-spin text-slate-300 text-[40px] absolute top-[calc(50%-20px)] left-[calc(50%-20px)]"></i>
                                    </label>
                                    <label v-else for="present_movie_path">
                                        <video class="aspect-video" controls>
                                            <source :src="'/storage/patients/'+params.tbl_patient.tbl_patient_id+'_'+params.tbl_patient.code+'/'+params.tbl_patient.present_movie_path">
                                            <p>動画を再生するには、videoタグをサポートしたブラウザが必要です。</p>
                                        </video>
                                        <p class="mt-[8px] text-center text-center"><label for="present_movie_path" class="cursor-pointer border rounded-sm border-main px-[15px] py-[3px] inline-block underline text-main font-bold text-[14px]">変更</label></p>
                                    </label>

                                    <input type="file" id="present_movie_path" accept="video/*" v-on:change="set_present($event,'present_movie_path')" />
                                    <div v-if="loading_progress.present_movie_path" class="bg-green-500/20 mt-[10px]" :style="loading_progress.present_movie_path"></div>

                                </div>
                            </div>
                            <div class="em-input-box">
                                <p class="em-input-head">プレゼントの画像</p>
                                <div class="w-[500px]" @dragover.prevent @drop="handle_drop($event,'present_photoart_path')">
                                    <label class="relative" for="present_photoart_path" v-if="!params.tbl_patient.present_photoart_path">
                                        <div class="aspect-video text-slate-400 text-[16px] bg-slate-100 block text-center border border-dashed border-slate-300 flex items-center justify-center hover:bg-slate-150 cursor-pointer leading-snug"><div><span class="font-bold">画像を設定</span><br /><span class="text-[13px]">拡張子:png,jpg / サイズ:2MB以下</span><span class="font-bold text-slate-250 text-[26px] mt-[10px] block">ドラッグ可</span></div></div>
                                        <i v-if="'present_photoart_path'==loading_input_key"
                                           class="fa-solid fa-spinner fa-spin text-slate-300 text-[40px] absolute top-[calc(50%-20px)] left-[calc(50%-20px)]"></i>
                                    </label>
                                    <label v-else for="present_photoart_path">
                                        <img :src="'/storage/patients/'+params.tbl_patient.tbl_patient_id+'_'+params.tbl_patient.code+'/'+params.tbl_patient.present_photoart_path">
                                        <p class="mt-[8px] text-center text-center"><label for="present_photoart_path" class="cursor-pointer border rounded-sm border-main px-[15px] py-[3px] inline-block underline text-main font-bold text-[14px]">変更</label></p>
                                    </label>

                                    <input type="file" id="present_photoart_path" accept="image/*" v-on:change="set_present($event,'present_photoart_path')" />
                                    <div v-if="loading_progress.present_photoart_path" class="bg-green-500/20 mt-[10px]" :style="loading_progress.present_photoart_path"></div>

                                </div>
                            </div>
                        </div>

                        <div class="em-input-box">
                            <p class="em-input-head">プレゼント日時</p>
                            <div>
                                <template v-if="params.tbl_patient.presented_at">
                                    {{params.tbl_patient.presented_at}}
                                </template>
                                <template v-else>--</template>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="em-input-box">
                    <p class="em-input-head">産院</p>
                    <div>
                        {{params.tbl_patient.mst_maternity.name}}
                    </div>
                </div>


                <div class="flex space-x-[20px]">
                    <div class="em-input-box">
                        <p class="em-input-head">ママの名前</p>
                        <div>
                            <template v-if="params.tbl_patient.name">
                                {{params.tbl_patient.name}}
                            </template><template v-else>--</template>

                            <template v-if="params.tbl_patient.roman_alphabet">
                                ({{params.tbl_patient.roman_alphabet}})
                            </template><template v-else> (--)</template>
                        </div>
                    </div>
                    <div class="em-input-box">
                        <p class="em-input-head">ベビーの名前</p>
                        <div>
                            <template v-if="params.tbl_patient.baby_name">
                                {{params.tbl_patient.baby_name}}
                            </template><template v-else>--</template>

                            <template v-if="params.tbl_patient.baby_roman_alphabet">
                                ({{params.tbl_patient.baby_roman_alphabet}})
                            </template><template v-else> (--)</template>
                        </div>
                    </div>
                    <div class="em-input-box">
                        <p class="em-input-head">コピー</p>
                        <ul>
                            <li><a href="javascript:void(0);" @click="path_copy" class="active:no-underline active:text-slate-300 hover:underline text-main font-bold">B誕_M名_ID</a></li>
                        </ul>
                    </div>
                </div>


                <div class="flex space-x-[20px]">
                    <div class="em-input-box">
                        <p class="em-input-head">ベビーの誕生日</p>
                        <div>
                            <template v-if="params.tbl_patient.birth_day">
                                {{params.tbl_patient.birth_day}}&nbsp;
                            </template><template v-else>--</template>

                            <template v-if="params.tbl_patient.birth_time">
                                 <span>&nbsp;</span>{{params.tbl_patient.birth_time}}
                            </template><template v-else> --</template>

                        </div>
                    </div>

                    <div class="em-input-box">
                        <p class="em-input-head">体重</p>
                        <div>
                            <template v-if="params.tbl_patient.weight">
                                {{params.tbl_patient.weight.toLocaleString()}}
                            </template><template v-else>--</template> g
                        </div>
                    </div>

                    <div class="em-input-box">
                        <p class="em-input-head">身長</p>
                        <div>
                            <template v-if="params.tbl_patient.height">
                                {{params.tbl_patient.height}}
                            </template><template v-else>--</template> cm
                        </div>
                    </div>

                    <div class="em-input-box">
                        <p class="em-input-head">性別</p>
                        <div>
                            <template v-if="params.tbl_patient.sex">
                                <template v-if="params.tbl_patient.sex=='1'">男の子</template>
                                <template v-else>女の子</template>
                            </template><template v-else>--</template>

                            第 <template v-if="params.tbl_patient.what_number">
                                {{params.tbl_patient.what_number}}
                            </template><template v-else>--</template> 子

                        </div>
                    </div>
                </div>


                <div class="flex space-x-[20px]">
                    <div class="em-input-box">
                        <p class="em-input-head">1ヵ月健診の日</p>
                        <div>
                            <template v-if="params.tbl_patient.health_check_date">
                                {{params.tbl_patient.health_check_date}}
                            </template><template v-else>--</template>
                        </div>
                    </div>

                    <div class="em-input-box">
                        <p class="em-input-head">インスタグラムの掲載</p>
                        <div>
                            <template v-if="params.tbl_patient.is_use_instagram">
                                <template v-if="params.tbl_patient.is_use_instagram==1">許可</template>
                                <template v-else>不許可</template>
                            </template><template v-else>--</template>
                        </div>
                    </div>
                </div>

                <div class="em-input-box">
                    <p class="em-input-head">伝えておきたいこと</p>
                    <div class="whitespace-pre-line">
                        <template v-if="params.tbl_patient.message">
                            {{params.tbl_patient.message}}
                        </template>
                        <template v-else>--</template>
                    </div>
                </div>


                <div class="em-input-box">
                    <p class="em-input-head">レビュー</p>
                    <div class="p-[20px] bg-slate-100 space-y-[15px]">
                        <div class="flex space-x-[20px]">
                            <div class="em-input-box">
                                <p class="em-input-head">平均点</p>
                                <div>
                                    <template v-if="params.tbl_patient.average_score">
                                        {{params.tbl_patient.average_score}}
                                    </template>
                                    <template v-else>--</template> 点
                                </div>
                            </div>

                            <div class="em-input-box">
                                <p class="em-input-head">amazon id</p>
                                <div>
                                    <template v-if="params.tbl_patient.amazon_id">
                                        {{params.tbl_patient.amazon_id}}
                                    </template>
                                    <template v-else>--</template>
                                </div>
                            </div>

                            <div class="em-input-box">
                                <p class="em-input-head">支払うポイント</p>
                                <div>
                                    <template v-if="params.tbl_patient.review_point">
                                        {{params.tbl_patient.review_point}}
                                    </template>
                                    <template v-else>--</template>
                                </div>
                            </div>
                        </div>

                        <div class="em-input-box">
                            <p class="em-input-head">レビュー</p>
                            <div>
                                <template v-if="params.tbl_patient.review">
                                    {{params.tbl_patient.review}}
                                </template>
                                <template v-else>--</template>
                            </div>
                        </div>

                        <div class="flex space-x-[20px]">
                            <div class="em-input-box">
                                <p class="em-input-head">支払い状態</p>
                                <div>
                                    {{ global.payment_statuses[params.tbl_patient.payment_status] }}
                                </div>
                            </div>

                            <div class="em-input-box">
                                <p class="em-input-head">支払い対応者</p>
                                <div>
                                    <template v-if="params.tbl_patient.tbl_user_payment_by">
                                        {{params.tbl_patient.tbl_user_payment_by.name}}
                                    </template>
                                    <template v-else>--</template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="em-input-box">
                    <p class="em-input-head">作業情報</p>
                    <div class="p-[20px] bg-slate-100 space-y-[15px]">
                        <div class="flex space-x-[20px]">
                            <div class="em-input-box">
                                <p class="em-input-head">現在の作業担当者</p>
                                <div>
                                    <template v-if="params.tbl_patient.tbl_user_working_by">
                                        {{params.tbl_patient.tbl_user_working_by.name}}
                                    </template>
                                    <template v-else>--</template>
                                </div>
                            </div>
                            <div class="em-input-box">
                                <p class="em-input-head">作業開始日時</p>
                                <div>
                                    <template v-if="params.tbl_patient.undertook_at">
                                        {{params.tbl_patient.undertook_at}}
                                    </template>
                                    <template v-else>--</template>
                                </div>
                            </div>
                        </div>

                        <div class="em-input-box">
                            <p class="em-input-head">レタッチした人</p>
                            <div>
                                <template v-if="params.tbl_patient.tbl_user_task_retouch_by">
                                    {{params.tbl_patient.tbl_user_task_retouch_by.name}}
                                </template>
                                <template v-else>--</template>
                            </div>
                        </div>

                        <div class="em-input-box">
                            <p class="em-input-head">作業完了日時</p>
                            <div>
                                <template v-if="params.tbl_patient.completed_at">
                                    {{params.tbl_patient.completed_at}}
                                </template>
                                <template v-else>--</template>
                            </div>
                        </div>


                    </div>
                </div>

                <div class="em-input-box">
                    <p class="em-input-head">システム情報</p>
                    <div class="p-[20px] bg-slate-100 space-y-[15px]">
                        <div class="flex space-x-[20px]">
                            <div class="em-input-box">
                                <p class="em-input-head">コード</p>
                                <div>
                                    <a class="text-main hover:underline" :href="global.front_url+'/'+params.tbl_patient.code" target="_blank">{{ params.tbl_patient.code }}</a>
                                </div>
                            </div>
                            <div class="em-input-box">
                                <p class="em-input-head">LINEユーザー名</p>
                                <div>
                                    {{params.tbl_patient.line_name}}
                                </div>
                            </div>
                            <div class="em-input-box">
                                <p class="em-input-head">LINE USER ID</p>
                                <div>
                                    {{params.tbl_patient.line_user_id}}
                                </div>
                            </div>
                        </div>

                        <div class="flex space-x-[20px]">
                            <div class="em-input-box">
                                <p class="em-input-head">レコードのRichmenu id</p>
                                <div>
                                    <template v-if="params.tbl_patient.richmenu_id">
                                        {{params.tbl_patient.richmenu_id}}
                                    </template>
                                    <template v-else>--</template>
                                </div>
                            </div>
                            <div class="em-input-box">
                                <p class="em-input-head">レスポンスされたrichmenu_id</p>
                                <div>
                                    <template v-if="params.richmenu_id">
                                        {{params.richmenu_id}}
                                    </template>
                                    <template v-else>--</template>
                                </div>
                            </div>
                            <div class="em-input-box">
                                <p class="em-input-head">設定中のリッチメニュー</p>
                                <div>
                                    <img :src="'/patients/'+params.tbl_patient.tbl_patient_id+'/richmenu_img'" width="250" alt="--" />
                                </div>
                            </div>


                        </div>

                        <div class="flex space-x-[20px]">
                            <div class="em-input-box">
                                <p class="em-input-head">提出日時</p>
                                <div>
                                    <template v-if="params.tbl_patient.submitted_at">
                                        {{params.tbl_patient.submitted_at}}
                                    </template>
                                    <template v-else>--</template>
                                </div>
                            </div>
                            <div class="em-input-box">
                                <p class="em-input-head">登録日時</p>
                                <div>
                                    {{params.tbl_patient.created_at}}
                                </div>
                            </div>
                            <div class="em-input-box">
                                <p class="em-input-head">更新日時</p>
                                <div>
                                    {{params.tbl_patient.updated_at}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="em-input-box">
                    <p class="em-input-head">提出データ</p>
                    <div class="p-[20px] bg-slate-100 space-y-[15px]">
                        <div class="em-input-box">
                            <p class="em-input-head">エコー写真</p>
                            <div class="flex flex-wrap gap-[10px] empty:before:content-['--']">
                                <template v-for="(medium,medium_key) in params.tbl_patient.tbl_patient_mediums">
                                    <div v-if="medium.type=='echo'">
                                        <a v-bind:href="medium.original_src" target="_blank"><img :src="medium.src" width="200" alt="" /></a>
                                        <p class="text-center"><a class="text-main font-bold hover:underline" href="javascript:void(0)" @click="delete_medium(medium)">削除</a></p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="em-input-box">
                            <p class="em-input-head">ネームカード</p>
                            <div class="flex flex-wrap gap-[10px] empty:before:content-['--']">
                                <template v-for="(medium,medium_key) in params.tbl_patient.tbl_patient_mediums">
                                    <div v-if="medium.type=='namecard'">
                                        <a v-bind:href="medium.original_src" target="_blank"><img :src="medium.src" width="200" alt="" /></a>
                                        <p class="text-center"><a class="text-main font-bold hover:underline" href="javascript:void(0)" @click="delete_medium(medium)">削除</a></p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="em-input-box">
                            <p class="em-input-head">出産前・出産中・出産直後</p>
                            <div class="flex flex-wrap gap-[10px] empty:before:content-['--']">

                                <template v-for="(medium,medium_key) in params.tbl_patient.tbl_patient_mediums">
                                    <div v-if="medium.type=='pregnancy'">
                                        <a v-bind:href="medium.original_src" target="_blank"><img :src="medium.src" width="200" alt="" /></a>
                                        <p class="text-center"><a class="text-main font-bold hover:underline" href="javascript:void(0)" @click="delete_medium(medium)">削除</a></p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="em-input-box">
                            <p class="em-input-head">ご自由にお好きなシーン</p>
                            <div class="flex flex-wrap gap-[10px] empty:before:content-['--']">
                                <template v-for="(medium,medium_key) in params.tbl_patient.tbl_patient_mediums">
                                    <div v-if="medium.type=='free'">
                                        <a v-bind:href="medium.original_src" target="_blank"><img :src="medium.src" width="200" alt="" /></a>
                                        <p class="text-center"><a class="text-main font-bold hover:underline" href="javascript:void(0)" @click="delete_medium(medium)">削除</a></p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="em-input-box">
                            <p class="em-input-head">バースフォトにしたい写真</p>
                            <div class="flex flex-wrap gap-[10px] empty:before:content-['--']">
                                <template v-for="(medium,medium_key) in params.tbl_patient.tbl_patient_mediums">
                                    <div v-if="medium.type=='photoart'">
                                        <a v-bind:href="medium.original_src" target="_blank"><img :src="medium.src" width="200" alt="" /></a>
                                        <p class="text-center"><a class="text-main font-bold hover:underline" href="javascript:void(0)" @click="delete_medium(medium)">削除</a></p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="em-input-box">
                            <p class="em-input-head">産声・動画の登録</p>
                            <div class="flex flex-wrap gap-[10px]
        [&_.nothing]:block
        [&_.item+.nothing]:hidden
        ">
                                <template v-for="(medium,medium_key) in params.tbl_patient.tbl_patient_mediums">
                                    <div v-if="medium.type=='first_cry'" class="item">
                                        <div class="text-[14px] font-bold text-center mb-[3px]">入れたい産声</div>
                                        <video class="aspect-video w-[350px]" controls>
                                            <source :src="medium.src">
                                            <p>動画を再生するには、videoタグをサポートしたブラウザが必要です。</p>
                                        </video>
                                        <p class="text-center"><a class="text-main font-bold hover:underline" href="javascript:void(0)" @click="delete_medium(medium)">削除</a></p>
                                    </div>
                                </template>
                                <div class="nothing w-[48.5%]">
                                    <div class="text-[14px] font-bold text-center mb-[3px]">入れたい産声</div>
                                    <div class="text-center mt-[3px] py-[2px] border font-bold border-dashed border-slate !text-slate text-[12px] py-[40px] bg-slate-50">未保存</div>
                                </div>

                                <template v-for="(medium,medium_key) in params.tbl_patient.tbl_patient_mediums">
                                    <div v-if="medium.type=='movie'" class="item">
                                        <div class="text-[14px] font-bold text-center mb-[3px]">動画(横アングル)</div>
                                        <video class="aspect-video w-[350px]" controls>
                                            <source :src="medium.src">
                                            <p>動画を再生するには、videoタグをサポートしたブラウザが必要です。</p>
                                        </video>
                                        <p class="text-center"><a class="text-main font-bold hover:underline" href="javascript:void(0)" @click="delete_medium(medium)">削除</a></p>
                                    </div>
                                </template>
                                <div class="nothing w-[48.5%]">
                                    <div class="text-[14px] font-bold text-center mb-[3px]">動画(横アングル)</div>
                                    <div class="text-center mt-[3px] py-[2px] border font-bold border-dashed border-slate !text-slate text-[12px] py-[40px] bg-slate-50">未保存</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="em-input-box">
                    <p class="em-input-head">LINEのログ</p>
                    <div>
                        <a class="text-main underline" :href="'/patients/'+params.tbl_patient.tbl_patient_id+'/line_log'" target="_blank">確認する</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="em-board">
            <div class="em-board-content space-y-[15px]">
                <div class="em-input-box">
                    <p class="em-input-head">LINEへ返信</p>
                    <div class="flex items-end space-x-[10px]">
                        <textarea class="w-[285px] h-[285px] text-[18px] leading-normal" name="description" cols="40" rows="4" v-model="line_text"></textarea>
                        <button class="em-btn px-[20px] py-[4px] bg-orange" :class="{'!bg-slate-300':line_text==''}" type="submit" @click="send_line">送信</button>
                    </div>
                </div>
            </div>
        </div>

<!--        <button class="em-btn px-[20px] py-[4px] bg-orange" type="submit" @click="test_line">テストライン</button>-->
    </div>

</template>

<script>

export default {
    name: "patients-edit",

    data(){
        return {
            params:params,
            line_text:'',
            file:null,
            is_show_save_msg:false,
            is_show_save_error_msg:false,
            loading_input_key:'',
            loading_progress:{
                present_movie_path:'',
                present_photoart_path:'',
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

        async set_present(e,key){
            this.file = e.target.files[0];
            this.save_present(key);

        },
        async handle_drop(e,key){
            e.preventDefault();
            this.file = e.dataTransfer.files[0];
            this.save_present(key);
        },

        async save_present(key){
            this.loading_input_key=key;
            await axios.post('/api/v1/g/patient/'+this.params.tbl_patient.tbl_patient_id+'/save_present'+'?api_token='+global.api_token,
                {
                    key:key,
                    file:this.file,
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

                this.loading_input_key='';
                this.loading_progress[key]='width:0%;';
            });
        },
        async send_line(){
            if(this.line_text==''){
                return;
            }
            if(window.confirm(this.params.tbl_patient.name+'さんにLINEを送信します。\nよろしいですか？')) {
                await axios.post('/api/v1/g/patient/'+this.params.tbl_patient.tbl_patient_id+'/send_line'+'?api_token='+global.api_token,
                    {
                        tbl_patient_id:this.params.tbl_patient.tbl_patient_id,
                        line_text:this.line_text
                    }
                ).then((response) => {//リクエストの成功
                    alert('送信が成功しました。');
                }).catch((error) => {//リクエストの失敗
                    alert('エラーが発生しました\n正しく送信できていない可能性があります。');
                }).finally(() => {
                    this.line_text='';
                });
            }
        },
        path_copy:function(){
            let txt = this.params.tbl_patient.birth_day + '_' + this.params.tbl_patient.name + '_' + this.params.tbl_patient.tbl_patient_id
            navigator.clipboard.writeText(txt).then(
                () => {
                    // コピーに成功したときの処理
                },
                () => {
                    // コピーに失敗したときの処理
                });
        },

        async save_memo(){
            await axios.post('/api/v1/g/patient/'+this.params.tbl_patient.tbl_patient_id+'/save_memo'+'?api_token='+global.api_token,
                {
                    tbl_patient_id:this.params.tbl_patient.tbl_patient_id,
                    memo:this.params.tbl_patient.memo
                }
            ).then((response) => {//リクエストの成功
                this.is_show_save_msg =true;
                setTimeout(() => { this.is_show_save_msg=false; },2000);
            }).catch((error) => {//リクエストの失敗
                this.is_show_save_error_msg =true;
                setTimeout(() => { this.is_show_save_error_msg=false; },2000);
            }).finally(() => {

            });
        },
        async delete_medium(medium){
            let t =this;
            if(window.confirm('この画像を削除します。\nよろしいですか？')) {


            await axios.post('/api/v1/g/patient/'+this.params.tbl_patient.tbl_patient_id+'/delete_medium'+'?api_token='+global.api_token,
                {
                    tbl_patient_id:this.params.tbl_patient.tbl_patient_id,
                    tbl_patient_medium_id:medium.tbl_patient_medium_id,
                }
                ).then((response) => {//リクエストの成功
                    this.params.tbl_patient.tbl_patient_mediums = response.data.tbl_patient.tbl_patient_mediums;
                }).catch((error) => {//リクエストの失敗
                }).finally(() => {
                });
            }

        },
        async test_line(){
            if(window.confirm(this.params.tbl_patient.name+'さんにテストライン送信します。\nよろしいですか？')) {
                // await axios.post('/api/v1/g/patient/'+this.params.tbl_patient.tbl_patient_id+'/google_review_remind'+'?api_token='+global.api_token,
                //     {}
                // ).then((response) => {//リクエストの成功
                //     alert('送信が成功しました。');
                // }).catch((error) => {//リクエストの失敗
                //     alert('エラーが発生しました\n正しく送信できていない可能性があります。');
                // }).finally(() => {
                // });
            }
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
