<template>
    <form class="em-board" action="/messages/regist" method="post" ref="form" enctype="multipart/form-data">
        <input type="hidden" name="_token" :value="csrfToken">
        <div class="em-board-content space-y-[15px] flex flex-col items-start">
            <p v-if="params.success!=''" class="bg-green-500 text-white p-[3px_5px]">{{params.success}}</p>
            <p v-if="params.error!=''" class="bg-red-500 text-white p-[3px_5px]">{{params.error}}</p>
            <div>
                <p class="font-bold mb-[5px]">送信先</p>
                <div>
                    <select name="mst_maternity_id" id="">
                        <option v-for="(mst_maternity) in params.mst_maternities" :value="mst_maternity.mst_maternity_id">{{mst_maternity.name}}</option>
                    </select>
                </div>
            </div>

            <div>
                <p class="font-bold mb-[5px]">送信する画像1</p>
                <div>
                    <label v-if="!send_image_1" class="relative" for="send_image_1">
                        <div class="w-[300px] aspect-square text-slate-400 text-[16px] bg-slate-100 block text-center border border-dashed border-slate-300 flex items-center justify-center hover:bg-slate-150 cursor-pointer leading-snug"><span class="font-bold">画像を設定</span></div>
                    </label>
                    <label v-else class="relative" for="send_image_1">
                        <img class="w-[300px] aspect-square object-contain" :src="send_image_1" alt="">
                    </label>
                    <input type="file" name="send_image_1" id="send_image_1" accept="image/*" @change="handle_file_1" />
                </div>
            </div>

            <div>
                <p class="font-bold mb-[5px]">送信する画像2</p>
                <div>
                    <label v-if="!send_image_2" class="relative" for="send_image_2">
                        <div class="w-[300px] aspect-square text-slate-400 text-[16px] bg-slate-100 block text-center border border-dashed border-slate-300 flex items-center justify-center hover:bg-slate-150 cursor-pointer leading-snug"><span class="font-bold">画像を設定</span></div>
                    </label>
                    <label v-else class="relative" for="send_image_2">
                        <img class="w-[300px] aspect-square object-contain" :src="send_image_2" alt="">
                    </label>
                    <input type="file" name="send_image_2" id="send_image_2" accept="image/*" @change="handle_file_2" />
                </div>
            </div>

            <div>
                <div>
                    <p class="font-bold mb-[5px]">メッセージ</p>
                    <div>
                        <textarea name="message" class="w-[600px] h-[160px]"></textarea>
                    </div>
                </div>
                <button class="em-btn mt-[8px] px-[30px] py-[8px] bg-orange" type="submit">送信</button>
            </div>
            <p v-if="params.success!=''" class="bg-green-500 text-white p-[3px_5px]">{{params.success}}</p>
            <p v-if="params.error!=''" class="bg-red-500 text-white p-[3px_5px]">{{params.error}}</p>
        </div>
    </form><!--end em-filter-->
</template>

<script>

export default {
    name: "app",

    //子コンポーネント
    components: {
    },

    data(){
        return {
            csrfToken: window.csrfToken,
            params:params,

            send_image_1:null,
            send_image_2:null,
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
        submit:function(){
            let t = this;
            this.params.search_params.page = 1;
            setTimeout(() => {t.$refs.form.submit();}, 5);
        },
        // text_copy:function(txt){
        //     navigator.clipboard.writeText(txt);
        // },

        handle_file_1(event) {
            const files = Array.from(event.target.files);
            this.send_image_1 = this.convert_to_base64_1(files);
        },
        convert_to_base64_1(files){
            files.forEach(file => {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = () => {
                    this.send_image_1 =  reader.result;
                };
            });
        },

        handle_file_2(event) {
            const files = Array.from(event.target.files);
            this.send_image_2 = this.convert_to_base64_2(files);
        },
        convert_to_base64_2(files){
            files.forEach(file => {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = () => {
                    this.send_image_2 =  reader.result;
                };
            });
        },

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
