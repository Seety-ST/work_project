/**
 * Created by bosco on 2016/5/26.
 * 图片横向瀑布流
 */
(function(window,name) {
//定义一些工具
    var C = {
        MerageObj: function (obj1, obj2) {
            for (var p in obj2) {
                try {
                    if (typeof(obj2[p]) == "object") {
                        obj1[p] = $.merageObj(obj1[p], obj2[p]);
                    } else {
                        obj1[p] = obj2[p];
                    }
                } catch (e) {
                    obj1[p] = obj2[p];
                }
            }
            return obj1;
        },
        isArray: function (o) {
            return Object.prototype.toString.call(o) === '[object Array]';
        }
    }




    /**
     * @param $list 列表的元素
     * @param opt
     * 1：part 每个子项的选择器
     * 2：img 子项里面图片的选择器
     * 3：padding_x:图片与图片的右填充
     * 4：resize 窗口改变大小的时候是否重定义布局
     * 5：no_focusend：不显示不符合规定的图片【默认是显示】
     * 6：maxheight:最大高度
     * 7：fn_nosrc：图片里面获取src为空的时候触发，传递当前图片标签，如果返回值，则吧返回的值当成图片的SRC
     * 8：maxscale 横向允许放大的倍率
     * @returns {boolean}
     *
     * @method
     * 1:doit(showall,is_all) 重新计算比例
     */
    var init = function ($list, opt) {
        if (!$list || !$list.size || $list.size() < 1)return false;
        if (!opt.part)return false;
        var self = this;
        C.MerageObj(self, opt);
        self.img = self.img || "img";
        self.$list = $list;
        self.maxscale = self.maxscale || 1;

        self.doit();

        if (opt.resize) {
            $(window).resize(function () {
                clearTimeout(self.ast);
                self.ast = setTimeout(function () {
                    self.doit();
                }, 200);
            });
            setTimeout(function () {
                self.doit(false, true);
            }, 100);
        }
    }

    /**
     * 对列表的图片进行定位
     * @param showall 如果为true,则强制输出最后几张图片
     * @param is_all 是否把之前已经计算尺寸的 重新计算
     */
    init.prototype.doit = function (showall, is_all) {
        var self = this;
        self.listwidth = self.$list.width();
        var A = [];
        self.showall = showall;
        self.$list.find(self.part).each(function (i, item) {
            if ($(item).data("has_show") && is_all !== true)return true;
            var $_cc_img = $(this).find(self.img);
            var cc_src = $_cc_img.attr("src") || "";
            //计算尺寸
            var cc_fn = function (src) {
                var cc_getsize = function () {
                    var cc_item = src.split("?")[1];
                    var cc_size = [];
                    if (cc_item) {
                        cc_size = cc_item.match(/(\d+)x(\d+)/i);
                    }
                    var cc_width = cc_size[1];
                    var cc_height = cc_size[2];
                    //如果是带有尺寸后缀的，那么就重定义尺寸
                    if (/http:\/\/.*?_\d+\.(jpg|png)/.test(src)) {
                        var cc_getpercent = src.match(/http:\/\/.*?_(\d+)\.(jpg|png)/);
                        //获取图片
                        var cc_getscale = parseInt(cc_getpercent[1]);
                        switch (cc_getscale) {
                            case 120:
                            case 165:
                            case 440:
                            case 640:
                            case 750:
                                if (cc_getscale <= cc_width) {
                                    cc_width = cc_getscale;
                                    cc_height = cc_size[2] / cc_size[1] * cc_width;
                                }
                                break;
                            case 145:
                            case 230:
                                cc_width = cc_getscale;
                                cc_height = cc_getscale;

                        }
                    }
                    A.push({
                        width: cc_width,
                        height: cc_height,
                        src: src
                    })
                }
                if (self.getsize) {
                    var cc_ret = self.getsize();
                    if (cc_ret && cc_ret.width) {
                        A.push({
                            width: cc_ret.width,
                            height: cc_ret.height,
                            src: src
                        })
                    }
                    else {
                        cc_getsize();
                    }
                }
                else {
                    cc_getsize();
                }

            }
            //检测URL是否带参数，带参数的直接计算尺寸
            if (cc_src.indexOf("?") > 0) {
                cc_fn(cc_src);
            }
            //不带参数的，通过fn_nosrc方法 获取SRC
            else {
                var cc_ret = self.fn_nosrc($_cc_img);
                //同样判断是否带尺寸的
                if (cc_ret && cc_ret.indexOf("?") > 0) {
                    cc_fn(cc_ret);
                }
                //不带尺寸的，使用里列表最小宽度的一半，作为默认尺寸
                else {
                    A.push({
                        width: self.listwidth * 0.5,
                        height: self.listwidth * 0.5
                    });
                }
            }
        });
        $.each(A, function (i, item) {
            item.use = false;
        })
        //根据所有图片的尺寸，重新计算出需要的宽度高度
        var cc_new_arr = self.count(A);
        var cc_cache = 0;
        var cc_over_i = 0;
        //给每张图片定义尺寸
        self.$list.find(self.part).each(function (i, item) {
            if ($(item).data("has_show") && is_all !== true)return true;
            var $_this = $(this);
            var cc_data = cc_new_arr[cc_over_i];
            cc_over_i++;
            //吧不显示的去掉
            if (cc_data && cc_data.hidden) {
                $_this.css("display", "none");
            }
            else if (cc_data && cc_data.width) {
                $_this.data("has_show", true)
                $_this.css({
                    "display": "block",
                    "width": cc_data.width,
                    "height": cc_data.height
                })
                if (!cc_data.last) {
                    $_this.css("margin-right", 10);
                }
                else {
                    $_this.css("margin-right", 0);
                }
            }
        });
    }

    init.prototype.check = function (A_obj, bool) {
        var self = this;

        if (A_obj) {
            var W = self.listwidth - (A_obj.length - 1) * self.padding_x;
            var h0 = 0;
            var cc_n = 0;
            //获取小高度
            $.each(A_obj, function (i, item) {
                if (item.height <= h0 || h0 == 0) {
                    h0 = item.height;
                    cc_n = i;
                }
            })


            //获取该高度下的宽度
            var wz = 0;
            $.each(A_obj, function (i, item) {
                wz += item.width / item.height * h0;
            })
            //计算扩展比例
            var t = W / wz;
            if (t > self.maxscale && !bool) {
                return false;
            }
            //计算后续高宽
            var cc_A_ret = [];
            var hz = Math.round(h0 * t);
            //判断高度是否符合要求
            if (self.maxheight && self.maxheight < hz && !bool) {
                //console.log(h0);
                return false;
            }
            var cc_cache = 0;
            //按比例计算宽度
            $.each(A_obj, function (i, item) {
                var cc_last = false;
                var cc_width = Math.round((item.width / item.height) * hz);
                cc_cache += cc_width;
                if (i >= A_obj.length - 1) {
                    cc_last = true;
                    cc_width = (W - cc_cache) + cc_width;
                }
                cc_A_ret.push({
                    width: cc_width,
                    height: hz,
                    last: cc_last //是否一行最后一个
                })
            });
            return cc_A_ret;
        }
    }

//按照比例计算新比例
    init.prototype.count = function (list, max) {
        var self = this;
        max = self.maximg || 99;
        if (C.isArray(list)) {
            var A_new = [];
            $.each(list, function (i, item) {
                //吧已经用的过滤掉
                if (item.use)return true;
                var cc_cache = [];
                cc_cache.push(item);
                for (var n = 1; n < max; n++) {
                    if (n + i >= list.length)break;
                    var cc_item = list[n + i];
                    if (cc_item) {
                        cc_cache.push(cc_item);
                        var cc_bool = false;//是否强制显示

                        //最后一个的时候,根据参数，是否强制输出
                        if (n + i >= list.length - 2 || n == max - 1) {
                            if (!self.no_focusend || self.showall) {
                                self.showall = false;
                                cc_bool = true;
                            }
                            // console.log(cc_bool);
                        }
                        //如果最后一行只剩下一个的时候，放到这一行输出
                        if (n + i == list.length - 2) {
                            cc_cache.push(list[n + i + 1]);
                        }
                        //检查是否符合要求
                        var cc_ret = self.check(cc_cache, cc_bool);
                        //符合要求，就添加到新数组
                        if (cc_ret) {
                            A_new = A_new.concat(cc_ret);
                            $.each(cc_cache, function (i, cc_item) {
                                cc_item.use = true;
                            })
                            break;
                        }
                        //不符合要求，且是最后一行的时候，或者最后一个，不满足条件的隐藏掉
                        else if (n + i >= list.length - 2 || n == max - 1) {
                            $.each(cc_cache, function (i, item) {
                                item.use = true;
                                A_new.push({
                                    hidden: true
                                })
                            });
                        }
                    }
                }
            })
            return A_new;
        }
    }

    window[name]=init;
})(window,"image_list");
