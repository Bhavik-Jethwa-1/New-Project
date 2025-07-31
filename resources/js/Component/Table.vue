<template>
    <div :class="['h-full', ...customClass, margin ? margin:'mt-5']">
        <div class="overflow-x-auto overflow-y-visible h-full border border-slate-300 rounded-md custom-scrollbar">
            <div class="inline-block min-w-full align-middle overscroll-none" :class="bodyHeight ? bodyHeight : ''">
                <div class="shadow overscroll-none">
                    <table class="min-w-full divide-y divide-gray-300 overscroll-none">
                        <thead class="bg-gray-50" v-if="head.length">
                            <tr>
                                <th
                                    v-for="(header, index) in head"
                                    scope="col"
                                    :class="[
                                        'text-left font-semibold text-gray-900 py-4 px-4 text-sm',
                                        header && header.classes ? header.classes : '',
                                        header && header.sortable ? 'cursor-pointer' : ''
                                    ]"
                                    :key="index"
                                    @click="header.sortable ? toggleSort(header.order_by) : null"
                                >   
                                    <template v-if="header!==null">
                                        <div :class="`flex items-center gap-2 h-full ${
                                            header.classes && (Array.isArray(header.classes)
                                            ? header.classes.some(cls => cls.includes('text-center'))
                                            : header.classes.includes('text-center'))
                                            ? 'justify-center'
                                            : 'justify-start'
                                        }`">
                                            <div>{{header.title ? header.title : header}}</div>
                                            <div class="flex flex-col" v-if="header.sortable">
                                                <i
                                                    :class="[order_by == header.order_by && order_type == 'asc' ? 'text-gray-500 hover:text-gray-600' : 'text-gray-300 hover:text-gray-400', 'cursor-pointer fa-solid fa-caret-up leading-[7px] text-[15px]']">
                                                </i>
                                                <i
                                                    :class="[order_by == header.order_by && order_type == 'desc' ? 'text-gray-500 hover:text-gray-600' : 'text-gray-300 hover:text-gray-400', 'cursor-pointer fa-solid fa-caret-down leading-[7px] text-[15px]']">
                                                </i>
                                            </div>
                                        </div>
                                    </template>
                                    <Skeleton v-else :count="1" />
                                </th>
                            </tr>
                        </thead>
                        <tbody :class="bodyHeight ? bodyHeight : ''" class="divide-y divide-gray-200 bg-white">
                            <slot />
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
 
<script>
export default{
    props:{
        customClass:{
            type: Array,
            default: []
        },
        head:{
            type: Array,
            default: []
        },
        margin:{
            type: String,
        },
        bodyHeight: {
            type: String
        }   
    },
    data() {
        return {
            order_by: null,
            order_type: 'asc',
        };
    },
    methods: {
        toggleSort(key) {
            if (this.order_by === key) {
                this.order_type = this.order_type === 'asc' ? 'desc' : 'asc';
            } else {
                this.order_by = key;
                this.order_type = 'asc';
            }
            this.$emit('sort', { order_by: this.order_by, order_type: this.order_type });
        },
        resetSortingData(){
            this.order_by = null,
            this.order_type = 'asc'
        }
    }
}
</script>
 
<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
    height: 6px;
    border-radius: 9999px;
}
 
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #e2e2e2;
    border-radius: 9999px;
}
 
.dark .custom-scrollbar::-webkit-scrollbar-thumb {
    background: #586781;
}
.dark .custom-scrollbar::-webkit-scrollbar {
    width: 6px;
    height: 6px;
    border-radius: 9999px;
    background: #374151;
}
</style>
 