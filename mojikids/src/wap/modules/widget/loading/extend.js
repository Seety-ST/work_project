import loading_vue from './loading.vue';

const Indicator = Vue.extend(loading_vue);
let instance;
let timer;

module.exports = {
  open(options = {}) {
    if (!instance) {
      instance = new Indicator({
        el: document.createElement('div')
      });
    }

    if (instance.visible) return;
    instance.text = typeof options === 'string' ? options : options.text || '';
    instance.spinnerType = options.spinnerType || 'snake';
    instance.timeout = options.timeout || 0;
    document.body.appendChild(instance.$el);
    if (timer) {
      clearTimeout(timer);
    }

    if(instance.timeout > 0)
    {
        setTimeout(function () {
            instance.visible = false;
        }, instance.timeout);
    }

    Vue.nextTick(() => {
      instance.visible = true;
    });
  },

  close() {
    if (instance) {
      instance.visible = false;
      timer = setTimeout(() => {
        if (instance.$el) {
          instance.$el.style.display = 'none';
        }
      }, 400);
    }
  }
};
