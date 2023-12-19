<script>
export default {

    data(){
        return {
            global:global
        }
    },

    methods:{
        merge_deeply:function(target, source, opts) {
            const isObject = obj => obj && typeof obj === 'object' && !Array.isArray(obj);
            const isConcatArray = opts && opts.concatArray;
            let result = Object.assign({}, target);
            if (isObject(target) && isObject(source)) {
                for (const [sourceKey, sourceValue] of Object.entries(source)) {
                    const targetValue = target[sourceKey];
                    if (isConcatArray && Array.isArray(sourceValue) && Array.isArray(targetValue)) {
                        result[sourceKey] = targetValue.concat(...sourceValue);
                    }
                    else if (isObject(sourceValue) && target.hasOwnProperty(sourceKey)) {
                        result[sourceKey] = this.merge_deeply(targetValue, sourceValue, opts);
                    }
                    else {
                        Object.assign(result, {[sourceKey]: sourceValue});
                    }
                }
            }
            return result;
        },
        is_json:function(data){
            try {
                JSON.parse(data);
            } catch (error) {
                return false;
            }
            return true;
        },
        is_image_extension:function(extension){
            if(
                extension.toLowerCase() == 'jpg'
                || extension.toLowerCase() == 'jpeg'
                || extension.toLowerCase() == 'png'
                || extension.toLowerCase() == 'gif'
            ){
                return true;
            }else{
                return false;
            }
        },
        generate_token:function(){
            let chars = 'abcdefghijklmnopqrstuvwxyz';
            let rand_str = '';
            for ( let i = 0; i < 32; i++ ) {
                rand_str += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            return rand_str;
        }
    }
}
</script>

