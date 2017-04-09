(function()
{
    var run_status = 'DEV' == 'DEV' ? false : true ;

    var version = new Date().getTime();

    require.resourceMap({
    "res": {
        "wap-kids:global/app/index": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/global/app/index.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/global/app/index.js",
            "type": "js"
        },
        "wap-kids:global/util/index": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/global/util/index.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/global/util/index.js",
            "type": "js"
        },
        "wap-kids:global/wxsdk/index": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/global/wxsdk/index.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/global/wxsdk/index.js",
            "type": "js"
        },
        "wap-kids:layout/footer/footer": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/layout/footer/footer.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/layout/footer/footer.js",
            "type": "js",
            "deps": [
                "wap-kids:modules/layout/footer/footer.css"
            ]
        },
        "wap-kids:modules/layout/footer/footer.css": {
            "uri": "http://localhost/mojikids/loc/static/wap/style/modules/layout/footer/footer.css",
            "url": "http://localhost/mojikids/loc/static/wap/style/modules/layout/footer/footer.css",
            "type": "css"
        },
        "wap-kids:layout/footer/index": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/layout/footer/index.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/layout/footer/index.js",
            "type": "js",
            "deps": [
                "wap-kids:layout/footer/footer"
            ]
        },
        "wap-kids:layout/list/index": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/layout/list/index.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/layout/list/index.js",
            "type": "js"
        },
        "wap-kids:mine/photos/index": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/mine/photos/index.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/mine/photos/index.js",
            "type": "js",
            "deps": [
                "wap-kids:mine/photos/photos"
            ]
        },
        "wap-kids:mine/photos/photos": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/mine/photos/photos.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/mine/photos/photos.js",
            "type": "js",
            "deps": [
                "wap-kids:widget/swipe/index",
                "wap-kids:widget/swipe/index.js"
            ]
        },
        "wap-kids:upload/index": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/upload/index.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/upload/index.js",
            "type": "js"
        },
        "wap-kids:widget/area/area": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/widget/area/area.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/widget/area/area.js",
            "type": "js",
            "deps": [
                "wap-kids:widget/picker/index",
                "wap-kids:widget/popup/index",
                "wap-kids:modules/widget/area/area.css",
                "wap-kids:widget/picker/index.js",
                "wap-kids:widget/popup/index.js"
            ]
        },
        "wap-kids:modules/widget/area/area.css": {
            "uri": "http://localhost/mojikids/loc/static/wap/style/modules/widget/area/area.css",
            "url": "http://localhost/mojikids/loc/static/wap/style/modules/widget/area/area.css",
            "type": "css"
        },
        "wap-kids:widget/area/index": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/widget/area/index.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/widget/area/index.js",
            "type": "js",
            "deps": [
                "wap-kids:widget/area/area"
            ]
        },
        "wap-kids:widget/date/date": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/widget/date/date.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/widget/date/date.js",
            "type": "js"
        },
        "wap-kids:widget/date/index": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/widget/date/index.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/widget/date/index.js",
            "type": "js",
            "deps": [
                "wap-kids:widget/date/date"
            ]
        },
        "wap-kids:widget/datepicker/datepicker": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/widget/datepicker/datepicker.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/widget/datepicker/datepicker.js",
            "type": "js",
            "deps": [
                "wap-kids:widget/picker/index",
                "wap-kids:widget/popup/index",
                "wap-kids:modules/widget/datepicker/datepicker.css",
                "wap-kids:widget/picker/index.js",
                "wap-kids:widget/popup/index.js"
            ]
        },
        "wap-kids:modules/widget/datepicker/datepicker.css": {
            "uri": "http://localhost/mojikids/loc/static/wap/style/modules/widget/datepicker/datepicker.css",
            "url": "http://localhost/mojikids/loc/static/wap/style/modules/widget/datepicker/datepicker.css",
            "type": "css"
        },
        "wap-kids:widget/datepicker/index": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/widget/datepicker/index.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/widget/datepicker/index.js",
            "type": "js",
            "deps": [
                "wap-kids:widget/datepicker/datepicker"
            ]
        },
        "wap-kids:widget/dialog/dialog": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/widget/dialog/dialog.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/widget/dialog/dialog.js",
            "type": "js",
            "deps": [
                "wap-kids:widget/popup/extend",
                "wap-kids:modules/widget/dialog/dialog.css",
                "wap-kids:widget/popup/extend.js"
            ]
        },
        "wap-kids:modules/widget/dialog/dialog.css": {
            "uri": "http://localhost/mojikids/loc/static/wap/style/modules/widget/dialog/dialog.css",
            "url": "http://localhost/mojikids/loc/static/wap/style/modules/widget/dialog/dialog.css",
            "type": "css"
        },
        "wap-kids:widget/dialog/extend": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/widget/dialog/extend.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/widget/dialog/extend.js",
            "type": "js",
            "deps": [
                "wap-kids:widget/dialog/dialog"
            ]
        },
        "wap-kids:widget/dialog/index": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/widget/dialog/index.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/widget/dialog/index.js",
            "type": "js",
            "deps": [
                "wap-kids:widget/dialog/extend"
            ]
        },
        "wap-kids:widget/go_top/go_top": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/widget/go_top/go_top.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/widget/go_top/go_top.js",
            "type": "js",
            "deps": [
                "wap-kids:modules/widget/go_top/go_top.css"
            ]
        },
        "wap-kids:modules/widget/go_top/go_top.css": {
            "uri": "http://localhost/mojikids/loc/static/wap/style/modules/widget/go_top/go_top.css",
            "url": "http://localhost/mojikids/loc/static/wap/style/modules/widget/go_top/go_top.css",
            "type": "css"
        },
        "wap-kids:widget/go_top/index": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/widget/go_top/index.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/widget/go_top/index.js",
            "type": "js",
            "deps": [
                "wap-kids:widget/go_top/go_top"
            ]
        },
        "wap-kids:widget/lazyload/extend": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/widget/lazyload/extend.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/widget/lazyload/extend.js",
            "type": "js"
        },
        "wap-kids:widget/lazyload/index": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/widget/lazyload/index.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/widget/lazyload/index.js",
            "type": "js",
            "deps": [
                "wap-kids:widget/lazyload/extend"
            ]
        },
        "wap-kids:widget/loading/extend": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/widget/loading/extend.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/widget/loading/extend.js",
            "type": "js",
            "deps": [
                "wap-kids:widget/loading/loading"
            ]
        },
        "wap-kids:widget/loading/index": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/widget/loading/index.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/widget/loading/index.js",
            "type": "js",
            "deps": [
                "wap-kids:widget/loading/extend"
            ]
        },
        "wap-kids:widget/loading/loading": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/widget/loading/loading.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/widget/loading/loading.js",
            "type": "js",
            "deps": [
                "wap-kids:modules/widget/loading/loading.css"
            ]
        },
        "wap-kids:modules/widget/loading/loading.css": {
            "uri": "http://localhost/mojikids/loc/static/wap/style/modules/widget/loading/loading.css",
            "url": "http://localhost/mojikids/loc/static/wap/style/modules/widget/loading/loading.css",
            "type": "css"
        },
        "wap-kids:widget/picker/draggable": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/widget/picker/draggable.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/widget/picker/draggable.js",
            "type": "js"
        },
        "wap-kids:widget/picker/emitter": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/widget/picker/emitter.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/widget/picker/emitter.js",
            "type": "js"
        },
        "wap-kids:widget/picker/index": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/widget/picker/index.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/widget/picker/index.js",
            "type": "js",
            "deps": [
                "wap-kids:widget/picker/picker"
            ]
        },
        "wap-kids:widget/picker/picker-slot": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/widget/picker/picker-slot.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/widget/picker/picker-slot.js",
            "type": "js",
            "deps": [
                "wap-kids:widget/picker/draggable",
                "wap-kids:widget/picker/translate",
                "wap-kids:global/util/index",
                "wap-kids:widget/picker/emitter",
                "wap-kids:modules/widget/picker/picker-slot.css",
                "wap-kids:widget/picker/draggable.js",
                "wap-kids:widget/picker/translate.js",
                "wap-kids:global/util/index.js",
                "wap-kids:widget/picker/emitter.js"
            ]
        },
        "wap-kids:modules/widget/picker/picker-slot.css": {
            "uri": "http://localhost/mojikids/loc/static/wap/style/modules/widget/picker/picker-slot.css",
            "url": "http://localhost/mojikids/loc/static/wap/style/modules/widget/picker/picker-slot.css",
            "type": "css"
        },
        "wap-kids:widget/picker/picker": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/widget/picker/picker.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/widget/picker/picker.js",
            "type": "js",
            "deps": [
                "wap-kids:widget/picker/picker-slot",
                "wap-kids:modules/widget/picker/picker.css",
                "wap-kids:widget/picker/picker-slot.js"
            ]
        },
        "wap-kids:modules/widget/picker/picker.css": {
            "uri": "http://localhost/mojikids/loc/static/wap/style/modules/widget/picker/picker.css",
            "url": "http://localhost/mojikids/loc/static/wap/style/modules/widget/picker/picker.css",
            "type": "css"
        },
        "wap-kids:widget/picker/translate": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/widget/picker/translate.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/widget/picker/translate.js",
            "type": "js"
        },
        "wap-kids:widget/popup/extend": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/widget/popup/extend.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/widget/popup/extend.js",
            "type": "js"
        },
        "wap-kids:widget/popup/index": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/widget/popup/index.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/widget/popup/index.js",
            "type": "js",
            "deps": [
                "wap-kids:widget/popup/popup"
            ]
        },
        "wap-kids:widget/popup/popup": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/widget/popup/popup.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/widget/popup/popup.js",
            "type": "js",
            "deps": [
                "wap-kids:widget/popup/extend",
                "wap-kids:modules/widget/popup/popup.css",
                "wap-kids:widget/popup/extend.js"
            ]
        },
        "wap-kids:modules/widget/popup/popup.css": {
            "uri": "http://localhost/mojikids/loc/static/wap/style/modules/widget/popup/popup.css",
            "url": "http://localhost/mojikids/loc/static/wap/style/modules/widget/popup/popup.css",
            "type": "css"
        },
        "wap-kids:widget/radio_test/index": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/widget/radio_test/index.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/widget/radio_test/index.js",
            "type": "js",
            "deps": [
                "wap-kids:widget/radio_test/radio"
            ]
        },
        "wap-kids:widget/radio_test/radio": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/widget/radio_test/radio.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/widget/radio_test/radio.js",
            "type": "js",
            "deps": [
                "wap-kids:modules/widget/radio_test/radio.css"
            ]
        },
        "wap-kids:modules/widget/radio_test/radio.css": {
            "uri": "http://localhost/mojikids/loc/static/wap/style/modules/widget/radio_test/radio.css",
            "url": "http://localhost/mojikids/loc/static/wap/style/modules/widget/radio_test/radio.css",
            "type": "css"
        },
        "wap-kids:widget/radio/index": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/widget/radio/index.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/widget/radio/index.js",
            "type": "js",
            "deps": [
                "wap-kids:widget/radio/radio"
            ]
        },
        "wap-kids:widget/radio/radio": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/widget/radio/radio.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/widget/radio/radio.js",
            "type": "js",
            "deps": [
                "wap-kids:modules/widget/radio/radio.css"
            ]
        },
        "wap-kids:modules/widget/radio/radio.css": {
            "uri": "http://localhost/mojikids/loc/static/wap/style/modules/widget/radio/radio.css",
            "url": "http://localhost/mojikids/loc/static/wap/style/modules/widget/radio/radio.css",
            "type": "css"
        },
        "wap-kids:widget/swipe/extend": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/widget/swipe/extend.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/widget/swipe/extend.js",
            "type": "js"
        },
        "wap-kids:widget/swipe/index": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/widget/swipe/index.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/widget/swipe/index.js",
            "type": "js",
            "deps": [
                "wap-kids:widget/swipe/extend",
                "wap-kids:widget/swipe/swipe",
                "wap-kids:widget/swipe/swipe-item"
            ]
        },
        "wap-kids:widget/swipe/items": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/widget/swipe/items.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/widget/swipe/items.js",
            "type": "js",
            "deps": [
                "wap-kids:widget/swipe/swipe-item"
            ]
        },
        "wap-kids:widget/swipe/swipe-item": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/widget/swipe/swipe-item.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/widget/swipe/swipe-item.js",
            "type": "js"
        },
        "wap-kids:widget/swipe/swipe": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/widget/swipe/swipe.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/widget/swipe/swipe.js",
            "type": "js",
            "deps": [
                "wap-kids:widget/swipe/extend",
                "wap-kids:modules/widget/swipe/swipe.css",
                "wap-kids:widget/swipe/extend.js"
            ]
        },
        "wap-kids:modules/widget/swipe/swipe.css": {
            "uri": "http://localhost/mojikids/loc/static/wap/style/modules/widget/swipe/swipe.css",
            "url": "http://localhost/mojikids/loc/static/wap/style/modules/widget/swipe/swipe.css",
            "type": "css"
        },
        "wap-kids:widget/toast/extend": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/widget/toast/extend.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/widget/toast/extend.js",
            "type": "js",
            "deps": [
                "wap-kids:widget/toast/toast"
            ]
        },
        "wap-kids:widget/toast/index": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/widget/toast/index.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/widget/toast/index.js",
            "type": "js",
            "deps": [
                "wap-kids:widget/toast/extend"
            ]
        },
        "wap-kids:widget/toast/toast": {
            "uri": "http://localhost/mojikids/loc/static/wap/modules/widget/toast/toast.js",
            "url": "http://localhost/mojikids/loc/static/wap/modules/widget/toast/toast.js",
            "type": "js",
            "deps": [
                "wap-kids:modules/widget/toast/toast.css"
            ]
        },
        "wap-kids:modules/widget/toast/toast.css": {
            "uri": "http://localhost/mojikids/loc/static/wap/style/modules/widget/toast/toast.css",
            "url": "http://localhost/mojikids/loc/static/wap/style/modules/widget/toast/toast.css",
            "type": "css"
        },
        "wap-kids:templates/ui/head.js": {
            "uri": "http://localhost/mojikids/loc/templates/wap/ui/head.js",
            "url": "http://localhost/mojikids/loc/templates/wap/ui/head.js",
            "type": "js"
        }
    },
    "pkg": {}
});

	console.log(require.__RESOURCES_MAP_OBJ);

    var __rmap = require.__RESOURCES_MAP_OBJ;
    var __global_list_map = [];

    for(var key in __rmap['res'])
    {
        if(__rmap['res'][key] && __rmap['res'][key]['uri'] &&
		( /wap-kids:global(.*)/.test(key) || /wap-kids:widget(.*)/.test(key) || /wap-kids:modules\/widget(.*)/.test(key)  ) )
        {
            __global_list_map.push(key);
        }

    }

    __yue_ls__.loader(__rmap,__global_list_map,run_status,version,'mojikids');

})();
