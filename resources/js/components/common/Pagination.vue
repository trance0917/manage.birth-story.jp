<template>
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center">
        <div v-if="pagination.from" class="mr-2">
            <span class="relative z-0 inline-flex">
                <template v-if="pagination.current_page==1">
                </template>
                <template v-else>
                    <a @click.prevent="$emit('page_link_click',pagination.current_page-1)" href="javascript:void(0);" class="relative inline-flex items-center px-1 py-1 text-sm font-medium text-slate-500 bg-white border border-slate-350 leading-2 hover:text-slate-400 focus:z-10 focus:outline-none focus:ring ring-slate-300 focus:border-blue-300 active:bg-slate-100 active:text-slate-500 transition ease-in-out duration-150">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" />
                        </svg>
                    </a>
                </template>

                <template v-for="(link,link_key) in pagination.links">
                    <template v-if="link.label!='pagination.previous' && link.label!='pagination.next'">
                        <span v-if="pagination.current_page == link.label">
                            <span class="relative inline-flex items-center px-2 py-1 -ml-px text-sm font-medium text-white bg-main border border-slate-350 cursor-default leading-2">{{ link.label }}</span>
                        </span>
                        <span v-else-if="'...' == link.label">
                            <span class="relative inline-flex items-center px-1 py-1 -ml-px text-sm font-medium border border-transparent cursor-default leading-2">{{ link.label }}</span>
                        </span>
                        <a v-else @click.prevent="$emit('page_link_click',link.label)" href="javascript:void(0);" class="relative inline-flex items-center px-2 py-1 -ml-px text-sm font-medium text-slate-700 bg-white border border-slate-350 leading-2 hover:text-slate-500 focus:z-10 focus:outline-none focus:ring ring-slate-300 focus:border-blue-300 active:bg-slate-100 active:text-slate-700 transition ease-in-out duration-150">
                            {{ link.label }}
                        </a>
                    </template>
                </template>

                <a v-if="pagination.next_page_url" @click.prevent="$emit('page_link_click',pagination.current_page+1)" href="javascript:void(0);" class="relative inline-flex items-center px-1 py-1 -ml-px text-sm font-medium text-slate-500 bg-white border border-slate-350 leading-2 hover:text-slate-400 focus:z-10 focus:outline-none focus:ring ring-slate-300 focus:border-blue-300 active:bg-slate-100 active:text-slate-500 transition ease-in-out duration-150" aria-label="{{ __('pagination.next') }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
                    </svg>
                </a>
            </span>
        </div>
        <div>
            <p class="text-sm text-slate-700 leading-2">
                <span class="font-medium">{{ pagination.from }}</span>～<span class="font-medium">{{ pagination.to }}</span>件
                /
                <span class="font-medium">{{ pagination.total }}</span>件
            </p>
        </div>
    </nav>
</template>

<script>
export default {
    name: "Pagination",
    props: ['pagination'],
    emits:['page_link_click'],
    // emits:['supplier_delete'],
    components: {

    },
    data() {
        return {
        };
    },

    mounted:function(){
    },

    computed: {
    },
    methods:{
    }
}
</script>

