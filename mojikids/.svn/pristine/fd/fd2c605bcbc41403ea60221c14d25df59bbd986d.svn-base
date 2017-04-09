<template>
<div class="mint-radiolist" @change="$emit('change', currentValue)">
    <label class="mint-radiolist-title" v-text="title"></label>
    <div v-for="option in options">
        <label class="mint-radiolist-label" slot="title">
        <span
          :class="{'is-right': align === 'right'}"
          class="mint-radio">
          <input
            class="mint-radio-input"
            type="radio"
            v-model="currentValue"
            :disabled="option.disabled"
            :value="option.value || option">
          <span class="mint-radio-core"></span>
        </span>
        <span class="mint-radio-label" v-text="option.label || option"></span>
      </label>
  </div>
</div>
</template>

<style lang="scss">
.mint-radiolist .mint-cell {
    padding: 0;
}
.mint-radiolist-label {
    display: block;
    padding: 0 10px;
}
.mint-radiolist-title {
    font-size: 12px;
    margin: 8px;
    display: block;
    color: #888;
}
.mint-radio {}
.mint-radio.is-right {
    float: right;
}
.mint-radio-label {
    vertical-align: middle;
    margin-left: 6px;
}
.mint-radio-input {
    display: none;
}
.mint-radio-input:checked + .mint-radio-core {
    background-color: #26a2ff;
    border-color: #26a2ff;
}
.mint-radio-input:checked + .mint-radio-core::after {
    background-color: #fff;
    -webkit-transform: scale(1);
    transform: scale(1);
}
.mint-radio-input[disabled] + .mint-radio-core {
    background-color: #d9d9d9;
    border-color: #ccc;
}
.mint-radio-core {
    display: inline-block;
    background-color: #fff;
    border-radius: 100%;
    border: 1px solid #ccc;
    position: relative;
    width: 20px;
    height: 20px;
    vertical-align: middle;
}
.mint-radio-core::after {
    content: " ";
    border-radius: 100%;
    top: 5px;
    left: 5px;
    position: absolute;
    width: 8px;
    height: 8px;
    -webkit-transition: -webkit-transform 0.2s;
    transition: -webkit-transform 0.2s;
    transition: transform 0.2s;
    transition: transform 0.2s, -webkit-transform 0.2s;
    -webkit-transform: scale(0);
    transform: scale(0);
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
    name: 'mt-radio',

    props: {
        title: String,
        align: String,
        options: {
            type: Array,
            required: true
        },
        value: String
    },

    data() {
        return {
            currentValue: this.value
        };
    },

    watch: {
        value(val) {
            this.currentValue = val;
        },

        currentValue(val) {
            this.$emit('input', val);
        }
    },
};
</script>
