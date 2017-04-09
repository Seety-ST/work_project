<!-- 模板 -->
<template>
<footer class="global-footer">
    <a href="#">
        <div class="box" :class="{ 'active' : index == 0 }">
            <span>首页</span>
        </div>
    </a>
    <a href="#">
        <div class="box" :class="{ 'active' : index == 1 }">
            <span>订单</span>
        </div>
    </a>
    <a href="#">
        <div class="box" :class="{ 'active' : index == 2 }">
            <span>我的</span>
        </div>
    </a>
</footer>
</template>

<!-- 样式 必须为scss -->
<style lang="scss">
.global-footer{
    .active{
        color:#f90;
    }
}
</style>

<!-- 脚本 必须为es6 -->
<script type="text/babel">
export default
{
    // 组件标记
    name: 'moji-footer',
    props:
    {
        class_name:
        {
            type: String,
            default: ''
        },
        index:
        {
            type : String,
            default:0
        }
    },
    data()
    {
        return {
            footer_show: false
        };
    },
    computed :
    {

    },
    methods :
    {

    }
}
</script>
