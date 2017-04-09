<template>
<div>
    <label class="radio-mod-1 " v-for="option in options" @change="$emit('change', currentValue)" :class="{'is-checked': option.value === currentValue}">
        <span class="el-radio-input ">
            <span class="radio-inner"></span>
            <input type="radio" class="el-radio-original" v-model="currentValue" :disabled="option.disabled" :value="option.value || option">
        </span>
        <span class="el-radio-label f14 color-ccc" v-text="option.value"></span>
    </label>
</div>
</template>

<style lang="scss">
    .radio-mod-1{
        .el-radio-input{
            position: relative;
            display:inline-block;
            .radio-inner{
                width:10px;
                height:10px;
                display:inline-block;
                vertical-align: middle;
                border: 2px solid #ccc;
                background-color: #fff;
                border-radius: 50%;
                position: relative;
                &:after{
                    width:6px;
                    height:6px;
                    border-radius: 50%;
                    content: "";
                    position: absolute;
                    background-color: #ffc430;
                    left: 50%;
                    top: 50%;
                    transform: translate(-50%,-50%) scale(0);
                    transition: transform .15s cubic-bezier(.71,-.46,.88,.6);
                }

            }
            .el-radio-original{
                width:14px;
                height:14px;
                opacity: 0;
                outline: none;
                position: absolute;
                z-index: -1;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                margin: 0;
            }
            .el-radio-label{
                padding-left: 5px;
                vertical-align: middle;
            }
        }
        &.is-checked{
            .radio-inner{
                border-color:#ffc430;
                &:after{
                    transform: translate(-50%,-50%) scale(1);
                }

            }
            
            .el-radio-label{
                color:#ffc430;

            }

        }
    }
</style>


<script>
/**
 * mt-radio
 * @module components/radio
 * @desc 单选框列表，依赖 cell 组件
 *
 * @param {string[], object[]} options - 选项数组，可以传入 [{label: 'label', value: 'value', disabled: true}] 或者 ['ab', 'cd', 'ef']
 * @param {string} value - 选中值
 * @param {string} title - 标题
 * @param {string} [align=left] - checkbox 对齐位置，`left`, `right`
 *
 * @example
 * <mt-radio v-model="value" :options="['a', 'b', 'c']"></mt-radio>
 */
export default {
    name: 'my-radio',

    props: {
        options: {
            type: Array,
            required: true
        },
        defalueval: String,
    },
    data() {
        return {
            currentValue: this.defalueval
        };
    },
    watch: {
        defalueval(val) {
            this.currentValue = val;
        },
        currentValue(val) {
            this.$emit('input', val);
            console.log(val);
        }
    },
    
    
};
</script>
