!function (e) {
    "function" == typeof define && define.amd ? define(["jquery"], e) : "object" == typeof exports ? e(require("jquery")) : e(window.jQuery || window.Zepto)
}(function (p) {
    function e() { }

    function l(e, t) {
        f.ev.on(n + e + y, t)
    }

    function d(e, t, n, a) {
        var o = document.createElement("div");
        return o.className = "mfp-" + e, n && (o.innerHTML = n), a ? t && t.appendChild(o) : (o = p(o), t && o.appendTo(t)), o
    }

    function v(e, t) {
        f.ev.triggerHandler(n + e, t), f.st.callbacks && (e = e.charAt(0).toLowerCase() + e.slice(1), f.st.callbacks[e] && f.st.callbacks[e].apply(f, p.isArray(t) ? t : [t]))
    }

    function m(e) {
        return e === t && f.currTemplate.closeBtn || (f.currTemplate.closeBtn = p(f.st.closeMarkup.replace("%title%", f.st.tClose)), t = e), f.currTemplate.closeBtn
    }

    function i() {
        p.magnificPopup.instance || ((f = new e).init(), p.magnificPopup.instance = f)
    }
    var f, a, _, o, h, t, c = "Close",
        u = "BeforeClose",
        g = "MarkupParse",
        n = "mfp",
        y = "." + n,
        b = "mfp-ready",
        r = "mfp-removing",
        s = !!window.jQuery,
        k = p(window);
    e.prototype = {
        constructor: e,
        init: function () {
            var e = navigator.appVersion;
            f.isLowIE = f.isIE8 = document.all && !document.addEventListener, f.isAndroid = /android/gi.test(e), f.isIOS = /iphone|ipad|ipod/gi.test(e), f.supportsTransition = function () {
                var e = document.createElement("p").style,
                    t = ["ms", "O", "Moz", "Webkit"];
                if (void 0 !== e.transition) return !0;
                for (; t.length;)
                    if (t.pop() + "Transition" in e) return !0;
                return !1
            }(), f.probablyMobile = f.isAndroid || f.isIOS || /(Opera Mini)|Kindle|webOS|BlackBerry|(Opera Mobi)|(Windows Phone)|IEMobile/i.test(navigator.userAgent), _ = p(document), f.popupsCache = {}
        },
        open: function (e) {
            var t;
            if (!1 === e.isObj) {
                f.items = e.items.toArray(), f.index = 0;
                var n, a = e.items;
                for (t = 0; t < a.length; t++)
                    if ((n = a[t]).parsed && (n = n.el[0]), n === e.el[0]) {
                        f.index = t;
                        break
                    }
            } else f.items = p.isArray(e.items) ? e.items : [e.items], f.index = e.index || 0;
            if (!f.isOpen) {
                f.types = [], h = "", e.mainEl && e.mainEl.length ? f.ev = e.mainEl.eq(0) : f.ev = _, e.key ? (f.popupsCache[e.key] || (f.popupsCache[e.key] = {}), f.currTemplate = f.popupsCache[e.key]) : f.currTemplate = {}, f.st = p.extend(!0, {}, p.magnificPopup.defaults, e), f.fixedContentPos = "auto" === f.st.fixedContentPos ? !f.probablyMobile : f.st.fixedContentPos, f.st.modal && (f.st.closeOnContentClick = !1, f.st.closeOnBgClick = !1, f.st.showCloseBtn = !1, f.st.enableEscapeKey = !1), f.bgOverlay || (f.bgOverlay = d("bg").on("click" + y, function () {
                    f.close()
                }), f.wrap = d("wrap").attr("tabindex", -1).on("click" + y, function (e) {
                    f._checkIfClose(e.target) && f.close()
                }), f.container = d("container", f.wrap)), f.contentContainer = d("content"), f.st.preloader && (f.preloader = d("preloader", f.container, f.st.tLoading));
                var o = p.magnificPopup.modules;
                for (t = 0; t < o.length; t++) {
                    var i = o[t];
                    i = i.charAt(0).toUpperCase() + i.slice(1), f["init" + i].call(f)
                }
                v("BeforeOpen"), f.st.showCloseBtn && (f.st.closeBtnInside ? (l(g, function (e, t, n, a) {
                    n.close_replaceWith = m(a.type)
                }), h += " mfp-close-btn-in") : f.wrap.append(m())), f.st.alignTop && (h += " mfp-align-top"), f.fixedContentPos ? f.wrap.css({
                    overflow: f.st.overflowY,
                    overflowX: "hidden",
                    overflowY: f.st.overflowY
                }) : f.wrap.css({
                    top: k.scrollTop(),
                    position: "absolute"
                }), !1 !== f.st.fixedBgPos && ("auto" !== f.st.fixedBgPos || f.fixedContentPos) || f.bgOverlay.css({
                    height: _.height(),
                    position: "absolute"
                }), f.st.enableEscapeKey && _.on("keyup" + y, function (e) {
                    27 === e.keyCode && f.close()
                }), k.on("resize" + y, function () {
                    f.updateSize()
                }), f.st.closeOnContentClick || (h += " mfp-auto-cursor"), h && f.wrap.addClass(h);
                var r = f.wH = k.height(),
                    s = {};
                if (f.fixedContentPos && f._hasScrollBar(r)) {
                    var c = f._getScrollbarSize();
                    c && (s.marginRight = c)
                }
                f.fixedContentPos && (f.isIE7 ? p("body, html").css("overflow", "hidden") : s.overflow = "hidden");
                var u = f.st.mainClass;
                return f.isIE7 && (u += " mfp-ie7"), u && f._addClassToMFP(u), f.updateItemHTML(), v("BuildControls"), p("html").css(s), f.bgOverlay.add(f.wrap).prependTo(f.st.prependTo || p(document.body)), f._lastFocusedEl = document.activeElement, setTimeout(function () {
                    f.content ? (f._addClassToMFP(b), f._setFocus()) : f.bgOverlay.addClass(b), _.on("focusin" + y, f._onFocusIn)
                }, 16), f.isOpen = !0, f.updateSize(r), v("Open"), e
            }
            f.updateItemHTML()
        },
        close: function () {
            f.isOpen && (v(u), f.isOpen = !1, f.st.removalDelay && !f.isLowIE && f.supportsTransition ? (f._addClassToMFP(r), setTimeout(function () {
                f._close()
            }, f.st.removalDelay)) : f._close())
        },
        _close: function () {
            v(c);
            var e = r + " " + b + " ";
            if (f.bgOverlay.detach(), f.wrap.detach(), f.container.empty(), f.st.mainClass && (e += f.st.mainClass + " "), f._removeClassFromMFP(e), f.fixedContentPos) {
                var t = {
                    marginRight: ""
                };
                f.isIE7 ? p("body, html").css("overflow", "") : t.overflow = "", p("html").css(t)
            }
            _.off("keyup.mfp focusin" + y), f.ev.off(y), f.wrap.attr("class", "mfp-wrap").removeAttr("style"), f.bgOverlay.attr("class", "mfp-bg"), f.container.attr("class", "mfp-container"), !f.st.showCloseBtn || f.st.closeBtnInside && !0 !== f.currTemplate[f.currItem.type] || !f.currTemplate.closeBtn || f.currTemplate.closeBtn.detach(), f.st.autoFocusLast && f._lastFocusedEl && p(f._lastFocusedEl).focus(), f.currItem = null, f.content = null, f.currTemplate = null, f.prevHeight = 0, v("AfterClose")
        },
        updateSize: function (e) {
            if (f.isIOS) {
                var t = document.documentElement.clientWidth / window.innerWidth,
                    n = window.innerHeight * t;
                f.wrap.css("height", n), f.wH = n
            } else f.wH = e || k.height();
            f.fixedContentPos || f.wrap.css("height", f.wH), v("Resize")
        },
        updateItemHTML: function () {
            var e = f.items[f.index];
            f.contentContainer.detach(), f.content && f.content.detach(), e.parsed || (e = f.parseEl(f.index));
            var t = e.type;
            if (v("BeforeChange", [f.currItem ? f.currItem.type : "", t]), f.currItem = e, !f.currTemplate[t]) {
                var n = !!f.st[t] && f.st[t].markup;
                v("FirstMarkupParse", n), f.currTemplate[t] = !n || p(n)
            }
            o && o !== e.type && f.container.removeClass("mfp-" + o + "-holder");
            var a = f["get" + t.charAt(0).toUpperCase() + t.slice(1)](e, f.currTemplate[t]);
            f.appendContent(a, t), e.preloaded = !0, v("Change", e), o = e.type, f.container.prepend(f.contentContainer), v("AfterChange")
        },
        appendContent: function (e, t) {
            (f.content = e) ? f.st.showCloseBtn && f.st.closeBtnInside && !0 === f.currTemplate[t] ? f.content.find(".mfp-close").length || f.content.append(m()) : f.content = e : f.content = "", v("BeforeAppend"), f.container.addClass("mfp-" + t + "-holder"), f.contentContainer.append(f.content)
        },
        parseEl: function (e) {
            var t, n = f.items[e];
            if ((n = n.tagName ? {
                el: p(n)
            } : (t = n.type, {
                data: n,
                src: n.src
            })).el) {
                for (var a = f.types, o = 0; o < a.length; o++)
                    if (n.el.hasClass("mfp-" + a[o])) {
                        t = a[o];
                        break
                    } n.src = n.el.attr("data-mfp-src"), n.src || (n.src = n.el.attr("href"))
            }
            return n.type = t || f.st.type || "inline", n.index = e, n.parsed = !0, f.items[e] = n, v("ElementParse", n), f.items[e]
        },
        addGroup: function (t, n) {
            function e(e) {
                e.mfpEl = this, f._openClick(e, t, n)
            }
            var a = "click.magnificPopup";
            (n = n || {}).mainEl = t, n.items ? (n.isObj = !0, t.off(a).on(a, e)) : (n.isObj = !1, n.delegate ? t.off(a).on(a, n.delegate, e) : (n.items = t).off(a).on(a, e))
        },
        _openClick: function (e, t, n) {
            if ((void 0 !== n.midClick ? n.midClick : p.magnificPopup.defaults.midClick) || !(2 === e.which || e.ctrlKey || e.metaKey || e.altKey || e.shiftKey)) {
                var a = void 0 !== n.disableOn ? n.disableOn : p.magnificPopup.defaults.disableOn;
                if (a)
                    if (p.isFunction(a)) {
                        if (!a.call(f)) return !0
                    } else if (k.width() < a) return !0;
                e.type && (e.preventDefault(), f.isOpen && e.stopPropagation()), n.el = p(e.mfpEl), n.delegate && (n.items = t.find(n.delegate)), f.open(n)
            }
        },
        updateStatus: function (e, t) {
            if (f.preloader) {
                a !== e && f.container.removeClass("mfp-s-" + a), t || "loading" !== e || (t = f.st.tLoading);
                var n = {
                    status: e,
                    text: t
                };
                v("UpdateStatus", n), e = n.status, t = n.text, f.preloader.html(t), f.preloader.find("a").on("click", function (e) {
                    e.stopImmediatePropagation()
                }), f.container.addClass("mfp-s-" + e), a = e
            }
        },
        _checkIfClose: function (e) {
            if (!p(e).hasClass("mfp-prevent-close")) {
                var t = f.st.closeOnContentClick,
                    n = f.st.closeOnBgClick;
                if (t && n) return !0;
                if (!f.content || p(e).hasClass("mfp-close") || f.preloader && e === f.preloader[0]) return !0;
                if (e === f.content[0] || p.contains(f.content[0], e)) {
                    if (t) return !0
                } else if (n && p.contains(document, e)) return !0;
                return !1
            }
        },
        _addClassToMFP: function (e) {
            f.bgOverlay.addClass(e), f.wrap.addClass(e)
        },
        _removeClassFromMFP: function (e) {
            this.bgOverlay.removeClass(e), f.wrap.removeClass(e)
        },
        _hasScrollBar: function (e) {
            return (f.isIE7 ? _.height() : document.body.scrollHeight) > (e || k.height())
        },
        _setFocus: function () {
            (f.st.focus ? f.content.find(f.st.focus).eq(0) : f.wrap).focus()
        },
        _onFocusIn: function (e) {
            if (e.target !== f.wrap[0] && !p.contains(f.wrap[0], e.target)) return f._setFocus(), !1
        },
        _parseMarkup: function (o, e, t) {
            var i;
            t.data && (e = p.extend(t.data, e)), v(g, [o, e, t]), p.each(e, function (e, t) {
                if (void 0 === t || !1 === t) return !0;
                if (1 < (i = e.split("_")).length) {
                    var n = o.find(y + "-" + i[0]);
                    if (0 < n.length) {
                        var a = i[1];
                        "replaceWith" === a ? n[0] !== t[0] && n.replaceWith(t) : "img" === a ? n.is("img") ? n.attr("src", t) : n.replaceWith(p("<img>").attr("src", t).attr("class", n.attr("class"))) : n.attr(i[1], t)
                    }
                } else o.find(y + "-" + e).html(t)
            })
        },
        _getScrollbarSize: function () {
            if (void 0 === f.scrollbarSize) {
                var e = document.createElement("div");
                e.style.cssText = "width: 99px; height: 99px; overflow: scroll; position: absolute; top: -9999px;", document.body.appendChild(e), f.scrollbarSize = e.offsetWidth - e.clientWidth, document.body.removeChild(e)
            }
            return f.scrollbarSize
        }
    }, p.magnificPopup = {
        instance: null,
        proto: e.prototype,
        modules: [],
        open: function (e, t) {
            return i(), (e = e ? p.extend(!0, {}, e) : {}).isObj = !0, e.index = t || 0, this.instance.open(e)
        },
        close: function () {
            return p.magnificPopup.instance && p.magnificPopup.instance.close()
        },
        registerModule: function (e, t) {
            t.options && (p.magnificPopup.defaults[e] = t.options), p.extend(this.proto, t.proto), this.modules.push(e)
        },
        defaults: {
            disableOn: 0,
            key: null,
            midClick: !1,
            mainClass: "",
            preloader: !0,
            focus: "",
            closeOnContentClick: !1,
            closeOnBgClick: !0,
            closeBtnInside: !0,
            showCloseBtn: !0,
            enableEscapeKey: !0,
            modal: !1,
            alignTop: !1,
            removalDelay: 0,
            prependTo: null,
            fixedContentPos: "auto",
            fixedBgPos: "auto",
            overflowY: "auto",
            closeMarkup: '<button title="%title%" type="button" class="mfp-close">&#215;</button>',
            tClose: "Close (Esc)",
            tLoading: "Loading...",
            autoFocusLast: !0
        }
    }, p.fn.magnificPopup = function (e) {
        i();
        var t = p(this);
        if ("string" == typeof e)
            if ("open" === e) {
                var n, a = s ? t.data("magnificPopup") : t[0].magnificPopup,
                    o = parseInt(arguments[1], 10) || 0;
                n = a.items ? a.items[o] : (n = t, a.delegate && (n = n.find(a.delegate)), n.eq(o)), f._openClick({
                    mfpEl: n
                }, t, a)
            } else f.isOpen && f[e].apply(f, Array.prototype.slice.call(arguments, 1));
        else e = p.extend(!0, {}, e), s ? t.data("magnificPopup", e) : t[0].magnificPopup = e, f.addGroup(t, e);
        return t
    };

    function C() {
        q && (x.after(q.addClass(w)).detach(), q = null)
    }
    var w, x, q, T = "inline";
    p.magnificPopup.registerModule(T, {
        options: {
            hiddenClass: "hide",
            markup: "",
            tNotFound: "Content not found"
        },
        proto: {
            initInline: function () {
                f.types.push(T), l(c + "." + T, function () {
                    C()
                })
            },
            getInline: function (e, t) {
                if (C(), e.src) {
                    var n = f.st.inline,
                        a = p(e.src);
                    if (a.length) {
                        var o = a[0].parentNode;
                        o && o.tagName && (x || (w = n.hiddenClass, x = d(w), w = "mfp-" + w), q = a.after(x).detach().removeClass(w)), f.updateStatus("ready")
                    } else f.updateStatus("error", n.tNotFound), a = p("<div>");
                    return e.inlineElement = a
                }
                return f.updateStatus("ready"), f._parseMarkup(t, {}, e), t
            }
        }
    });
    var O;
    p.magnificPopup.registerModule("zoom", {
        options: {
            enabled: !1,
            easing: "ease-in-out",
            duration: 300,
            opener: function (e) {
                return e.is("img") ? e : e.find("img")
            }
        },
        proto: {
            initZoom: function () {
                var e, i = f.st.zoom,
                    t = ".zoom";
                if (i.enabled && f.supportsTransition) {
                    function n(e) {
                        var t = e.clone().removeAttr("style").removeAttr("class").addClass("mfp-animated-image"),
                            n = "all " + i.duration / 1e3 + "s " + i.easing,
                            a = {
                                position: "fixed",
                                zIndex: 9999,
                                left: 0,
                                top: 0,
                                "-webkit-backface-visibility": "hidden"
                            },
                            o = "transition";
                        return a["-webkit-" + o] = a["-moz-" + o] = a["-o-" + o] = a[o] = n, t.css(a), t
                    }

                    function a() {
                        f.content.css("visibility", "visible")
                    }
                    var o, r, s = i.duration;
                    l("BuildControls" + t, function () {
                        if (f._allowZoom()) {
                            if (clearTimeout(o), f.content.css("visibility", "hidden"), !(e = f._getItemToZoom())) return void a();
                            (r = n(e)).css(f._getOffset()), f.wrap.append(r), o = setTimeout(function () {
                                r.css(f._getOffset(!0)), o = setTimeout(function () {
                                    a(), setTimeout(function () {
                                        r.remove(), e = r = null, v("ZoomAnimationEnded")
                                    }, 16)
                                }, s)
                            }, 16)
                        }
                    }), l(u + t, function () {
                        if (f._allowZoom()) {
                            if (clearTimeout(o), f.st.removalDelay = s, !e) {
                                if (!(e = f._getItemToZoom())) return;
                                r = n(e)
                            }
                            r.css(f._getOffset(!0)), f.wrap.append(r), f.content.css("visibility", "hidden"), setTimeout(function () {
                                r.css(f._getOffset())
                            }, 16)
                        }
                    }), l(c + t, function () {
                        f._allowZoom() && (a(), r && r.remove(), e = null)
                    })
                }
            },
            _allowZoom: function () {
                return "image" === f.currItem.type
            },
            _getItemToZoom: function () {
                return !!f.currItem.hasSize && f.currItem.img
            },
            _getOffset: function (e) {
                var t, n = (t = e ? f.currItem.img : f.st.zoom.opener(f.currItem.el || f.currItem)).offset(),
                    a = parseInt(t.css("padding-top"), 10),
                    o = parseInt(t.css("padding-bottom"), 10);
                n.top -= p(window).scrollTop() - a;
                var i = {
                    width: t.width(),
                    height: (s ? t.innerHeight() : t[0].offsetHeight) - o - a
                };
                return void 0 === O && (O = void 0 !== document.createElement("p").style.MozTransform), O ? i["-moz-transform"] = i.transform = "translate(" + n.left + "px," + n.top + "px)" : (i.left = n.left, i.top = n.top), i
            }
        }
    }), i()
}),
    function (v) {
        v(document).ready(function () {
            var c = {
                set: function (e, t, n) {
                    var a, o, i, r, s;
                    r = n ? ((i = new Date).setTime(i.getTime() + 24 * n * 60 * 60 * 1e3), "; expires=" + i.toGMTString()) : "", 1 === (s = location.host).split(".").length ? document.cookie = e + "=" + t + r + "; path=/" : ((o = s.split(".")).shift(), a = "." + o.join("."), document.cookie = e + "=" + t + r + "; path=/; domain=" + a, null != c.get(e) && c.get(e) == t || (a = "." + s, document.cookie = e + "=" + t + r + "; path=/; domain=" + a))
                },
                get: function (e) {
                    for (var t = e + "=", n = document.cookie.split(";"), a = 0; a < n.length; a++) {
                        for (var o = n[a];
                            " " == o.charAt(0);) o = o.substring(1, o.length);
                        if (0 == o.indexOf(t)) return o.substring(t.length, o.length)
                    }
                    return null
                },
                erase: function (e) {
                    c.set(e, "", -1)
                }
            };

            function a(e) {
                var t = v(".summary.entry-summary > .cart"),
                    n = v(".devvn_prod_variable .cart", e);
                v("select, input, textarea", t).each(function () {
                    var e = v(this).attr("name"),
                        t = v(this).val();
                    v('[name="' + e + '"]', n).val(t)
                }), n.trigger("check_variations"), p(e)
            }

            function o(e) {
                var n = v(".summary.entry-summary > .cart"),
                    t = v(".devvn_prod_variable .cart", e);
                v("select, input, textarea", t).each(function () {
                    var e = v(this).attr("name"),
                        t = v(this).val();
                    v('[name="' + e + '"]', n).val(t)
                }), n.trigger("check_variations"), p(e)
            }

            function p(e) {
                var t = v(".devvn_prod_variable .cart", e).data("product_variations"),
                    n = v('.devvn_prod_variable input[name="quantity"]', e).val(),
                    a = v('.devvn_prod_variable input[name="variation_id"]', e).val(),
                    o = total = ship = 0;
                (v("#enable_ship", e).val() || 0) && 0 < v(".popup_quickbuy_shipping_calc", e).length && 0 < v('.popup_quickbuy_shipping_calc input[name="shipping_method[0]"]', e).length && (ship = parseInt(v('.popup_quickbuy_shipping_calc input[name="shipping_method[0]"]:checked', e).data("cost")), ship || 1 != v('.popup_quickbuy_shipping_calc input[name="shipping_method[0]"]', e).length || (ship = parseInt(v('.popup_quickbuy_shipping_calc input[name="shipping_method[0]"]', e).data("cost")))), t ? v(t).each(function (e, t) {
                    t.variation_id == a && t.variation_is_active && t.variation_is_visible && (o = t.display_price)
                }) : o = v(".devvn_prod_variable", e).data("simpleprice");
                var i = parseFloat(v(".coupon_amout_val", e).val()) || 0;
                total = o * n + ship - i, v(".popup_quickbuy_total_calc", e).html(l(total.toFixed(0), 0, "", ".") + " " + devvn_quickbuy_array.currency_format), v("#order_total", e).val(total)
            }

            function l(e, t, n, a) {
                t = devvn_quickbuy_array.num_decimals, n = devvn_quickbuy_array.price_decimal, a = devvn_quickbuy_array.price_thousand, e = (e + "").replace(/[^0-9+\-Ee.]/g, "");
                var o, i, r, s = isFinite(+e) ? +e : 0,
                    c = isFinite(+t) ? Math.abs(t) : 0,
                    u = void 0 === a ? "," : a,
                    p = void 0 === n ? "." : n,
                    l = "";
                return 3 < (l = (c ? (o = s, i = c, r = Math.pow(10, i), "" + Math.round(o * r) / r) : "" + Math.round(s)).split("."))[0].length && (l[0] = l[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, u)), (l[1] || "").length < c && (l[1] = l[1] || "", l[1] += new Array(c - l[1].length + 1).join("0")), l.join(p)
            }

            function i() {
                0 < v("body .devvn_prod_variable").length && 0 == v("body .devvn_prod_variable .quantity.buttons_added .screen-reader-text").length && v("body .devvn_prod_variable .quantity.buttons_added").append('<label class="screen-reader-text">' + devvn_quickbuy_array.qty_text + "</label>"), v("body .devvn-popup-quickbuy").each(function () {
                    var o = v(this),
                        e = v(".devvn_cusstom_info", o);
                    e.validate({
                        rules: {
                            "customer-name": {
                                required: !0,
                                maxlength: 100
                            },
                            "customer-phone": {
                                required: {
                                    depends: function () {
                                        return v(this).val(v.trim(v(this).val())), !0
                                    }
                                },
                                vietnamphone: !0
                            },
                            "customer-valid-phone": {
                                required: {
                                    depends: function () {
                                        return v(this).val(v.trim(v(this).val())), !0
                                    }
                                },
                                vietnamphone: !0
                            },
                            "customer-quan": {
                                required: function (e) {
                                    return 1 == v("#require_district").val()
                                }
                            },
                            "customer-xa": {
                                required: function (e) {
                                    return 1 == v("#require_village").val()
                                }
                            },
                            "customer-address": {
                                required: function (e) {
                                    return 1 == v("#require_address").val()
                                }
                            },
                            "customer-email": {
                                required: function (e) {
                                    return 1 == v(e).data("required")
                                },
                                customemail: !0
                            }
                        },
                        messages: {
                            "customer-name": devvn_quickbuy_array.name_text,
                            "customer-phone": devvn_quickbuy_array.phone_text,
                            "customer-valid-phone": {
                                required: devvn_quickbuy_array.valid_phone_text,
                                equalTo: devvn_quickbuy_array.valid_phone_text_equalto
                            },
                            "customer-email": devvn_quickbuy_array.email_text,
                            "customer-quan": devvn_quickbuy_array.quan_text,
                            "customer-xa": devvn_quickbuy_array.xa_text,
                            "customer-address": devvn_quickbuy_array.address_text
                        },
                        errorLabelContainer: v(".devvn_quickbuy_mess", e)
                    });
                    var i = v("#enable_ship", o).val() || 0;
                    if (0 < v("#devvn_city", o).length) {
                        var r = !1,
                            s = v("#prod_nonce", o).val(),
                            c = 0;
                        0 < v('button.single_add_to_cart_button[name="add-to-cart"]', o).length && (c = parseInt(v('button.single_add_to_cart_button[name="add-to-cart"]', o).val())), v("#devvn_city", o).on("change", function (e) {
                            var t = e.val;
                            if ((t = t || v("#devvn_city option:selected", o).val()) && !r) {
                                var n = v(".devvn_prod_variable .cart", o).serialize();
                                v.ajax({
                                    type: "post",
                                    dataType: "json",
                                    url: devvn_quickbuy_array.ajaxurl,
                                    data: {
                                        action: "quickbuy_load_diagioihanhchinh",
                                        matp: t,
                                        getvalue: 1,
                                        product_info: n,
                                        nonce: s,
                                        prod_id: c
                                    },
                                    context: this,
                                    beforeSend: function () {
                                        v(".popup-customer-info", o).addClass("popup_loading"), r = !0
                                    },
                                    success: function (e) {
                                        if (v("#devvn_district", o).html(""), v("#devvn_ward", o).html('<option value="">Xã/phường</option>'), e.success) {
                                            var t = e.data.list_district,
                                                n = new Option("Quận/huyện", "");
                                            v("#devvn_district", o).append(n), v.each(t, function (e, t) {
                                                var n = new Option(t.name, t.maqh);
                                                v("#devvn_district", o).append(n)
                                            }), i && e.data.shipping && v(".popup_quickbuy_shipping_calc", o).html(e.data.shipping)
                                        }
                                        r = !1, v(".popup-customer-info", o).removeClass("popup_loading"), p(o)
                                    }
                                })
                            }
                        }), 0 < v("#devvn_district", o).length && v("#devvn_district", o).on("change", function (e) {
                            var t = e.val;
                            t = t || v("#devvn_district option:selected", o).val();
                            var n = v("#devvn_city option:selected", o).val();
                            if (t && !r) {
                                var a = v(".devvn_prod_variable .cart", o).serialize();
                                v.ajax({
                                    type: "post",
                                    dataType: "json",
                                    url: devvn_quickbuy_array.ajaxurl,
                                    data: {
                                        action: "quickbuy_load_diagioihanhchinh",
                                        matp: n,
                                        maqh: t,
                                        getvalue: 2,
                                        product_info: a,
                                        nonce: s,
                                        prod_id: c
                                    },
                                    context: this,
                                    beforeSend: function () {
                                        v(".popup-customer-info", o).addClass("popup_loading"), r = !0
                                    },
                                    success: function (e) {
                                        if (v("#devvn_ward", o).html(""), e.success) {
                                            var t = e.data.list_district,
                                                n = new Option("Xã/phường", "");
                                            v("#devvn_ward", o).append(n), v.each(t, function (e, t) {
                                                var n = new Option(t.name, t.xaid);
                                                v("#devvn_ward", o).append(n)
                                            }), i && e.data.shipping && v(".popup_quickbuy_shipping_calc", o).html(e.data.shipping)
                                        }
                                        r = !1, v(".popup-customer-info", o).removeClass("popup_loading"), p(o)
                                    }
                                })
                            }
                        }), v(window).on("load", function () {
                            v("#devvn_city", o).trigger("change")
                        }), v(".devvn_prod_variable .cart", o).on("change", function (e) {
                            if (i) {
                                var t = v("#devvn_district option:selected", o).val(),
                                    n = v("#devvn_city option:selected", o).val(),
                                    a = v(".devvn_prod_variable .cart", o).serialize();
                                v.ajax({
                                    type: "post",
                                    dataType: "json",
                                    url: devvn_quickbuy_array.ajaxurl,
                                    data: {
                                        action: "quickbuy_load_diagioihanhchinh",
                                        matp: n,
                                        maqh: t,
                                        getvalue: 1,
                                        product_info: a,
                                        nonce: s,
                                        prod_id: c
                                    },
                                    context: this,
                                    beforeSend: function () {
                                        v(".popup-customer-info", o).addClass("popup_loading")
                                    },
                                    success: function (e) {
                                        e.success && i && e.data.shipping && v(".popup_quickbuy_shipping_calc", o).html(e.data.shipping), v(".popup-customer-info", o).removeClass("popup_loading"), p(o)
                                    }
                                })
                            }
                        })
                    }
                    v("#devvn_cusstom_info, .devvn_prod_variable .cart, input.variation_id", o).on("change", function () {
                        p(o)
                    }), 0 < v(".devvn-form-quickbuy-inline").length && p(o)
                })
            }

            function r(e) {
                var t = c.get("quickbuy_infor");
                t && (t = v.parseJSON(t), v('input[name="customer-name"]', e).val() || v('input[name="customer-name"]', e).val(t.name), v('input[name="customer-phone"]', e).val() || v('input[name="customer-phone"]', e).val(t.phone), v('input[name="customer-email"]', e).val() || v('input[name="customer-email"]', e).val(t.email), v('input[name="customer-address"]', e).val() || v('input[name="customer-address"]', e).val(t.address))
            }
            v("body").on("click", ".devvn_buy_now", function () {
                var t = v(this).data("id"),
                    n = v("#popup_content_" + t);
                v(this).hasClass("devvn_buy_now_ajax") ? v.ajax({
                    type: "post",
                    dataType: "html",
                    url: devvn_quickbuy_array.ajaxurl,
                    data: {
                        action: "devvn_form_quickbuy",
                        prodid: t
                    },
                    context: this,
                    beforeSend: function () {
                        v(this).addClass("popup_loading")
                    },
                    success: function (e) {
                        //console.log(Math.random());
                        v.magnificPopup.open({
                            items: {
                                src: e,
                                type: "inline"
                            },
                            showCloseBtn: !1,
                            closeOnBgClick: !1,
                            mainClass: "le-van-toan",
                            callbacks: {
                                open: function () {
                                    var e = v("#popup_content_" + t);
                                    i(), v("#devvn_city", e).trigger("change"), p(e), r(e), "undefined" != typeof wc_add_to_cart_variation_params && v(".variations_form").each(function () {
                                        v(this).wc_variation_form();
                                        WGR_after_load_devvn_quick_cart();
                                    }), a(e)
                                },
                                close: function () {
                                    o(n)
                                }
                            }
                        }), v(this).removeClass("popup_loading")
                    },
                    error: function (e, t, n) {
                        v(this).removeClass("popup_loading")
                    }
                }) : v.magnificPopup.open({
                    items: {
                        src: "#popup_content_" + t,
                        type: "inline"
                    },
                    showCloseBtn: !1,
                    closeOnBgClick: !1,
                    mainClass: "le-van-toan",
                    callbacks: {
                        open: function () {
                            a(n), r(n), (v("#enable_ship", n).val() || 0) && 0 < v(".popup_quickbuy_shipping_calc", n).length && v('.popup_quickbuy_shipping_calc input[name="shipping_method[0]"]', n).length <= 0 && v("#devvn_city", n).trigger("change");
                            WGR_after_load_devvn_quick_cart();
                        },
                        close: function () {
                            o(n)
                        }
                    }
                })
            }), v(document).on("click", ".devvn-popup-close", function (e) {
                e.preventDefault(), v.magnificPopup.close()
            }), v.validator.addMethod("vietnamphone", function (e, t) {
                return /^0+(\d{9,10})$/.test(e)
            }, "Số điện thoại không đúng định dạng."), v.validator.addMethod("customemail", function (e, t) {
                return "" == e || /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(e)
            }, "Định dạng email không đúng."), i();
            var u = !1;
            v("body").on("click", ".devvn-order-btn", function () {
                var a = v(this).closest(".devvn-popup-quickbuy"),
                    e = v(".devvn_cusstom_info", a),
                    t = v('.devvn_prod_variable .cart input[name="variation_id"]', a).val();
                if ("string" == typeof t && "0" != t && "" != t || void 0 === t)
                    if (0 < v(".devvn_prod_variable .out-of-stock", a).length) alert(devvn_quickbuy_array.out_of_stock_mess);
                    else {
                        if (!e.valid()) return;
                        var n = v("#prod_nonce", a).val(),
                            o = v("#prod_id", a).val(),
                            i = v("#devvn_cusstom_info", a).serialize(),
                            r = v(".devvn_prod_variable .cart", a).serialize(),
                            s = {
                                name: v('input[name="customer-name"]', a).val() || "",
                                gender: v('input[name="customer-gender"]:checked', a).val() || "",
                                phone: v('input[name="customer-phone"]', a).val() || "",
                                email: v('input[name="customer-email"]', a).val() || "",
                                address: v('input[name="customer-address"]', a).val() || ""
                            };
                        u || v.ajax({
                            type: "post",
                            dataType: "json",
                            url: devvn_quickbuy_array.ajaxurl,
                            data: {
                                action: "devvn_quickbuy",
                                prod_id: o,
                                customer_info: i,
                                product_info: r,
                                nonce: n
                            },
                            context: this,
                            beforeSend: function () {
                                u = !0, v(".devvn-order-btn", a).addClass("loading")
                            },
                            success: function (e) {
                                //console.log(Math.random());
                                console.log(e);
                                e.success ? (c.set("quickbuy_infor", JSON.stringify(s)), e.data.gotothankyou && e.data.thankyou_link ? window.location.href = e.data.thankyou_link : v(".devvn-popup-content", a).html(e.data.content)) : alert(devvn_quickbuy_array.popup_error), u = !1, v(".devvn-order-btn", a).removeClass("loading")
                            },
                            error: function (e, t, n) {
                                console.log(e);
                                console.log(t);
                                console.log(n);
                                alert(devvn_quickbuy_array.popup_error), u = !1, v(".devvn-order-btn", a).removeClass("loading")
                            }
                        })
                    }
                else alert(wc_add_to_cart_variation_params.i18n_make_a_selection_text);
                return !1
            });
            var d = !1;
            v("body").on("click", ".apply_coupon", function () {
                var t = v(this).closest(".devvn-popup-quickbuy"),
                    a = v(".customer_coupon_wrap", t),
                    n = v(".customer_coupon_field_mess ", t),
                    o = v(".quickbuy_coupon_mess_amout ", t),
                    i = v(".quickbuy_coupon_mess", t),
                    r = v(".coupon_amout_val", t),
                    s = v(".quickbuy_coupon_amout", t),
                    e = v(".customer-coupon", t).val(),
                    c = v(".devvn_prod_variable .cart", t).serialize(),
                    u = 0;
                return 0 < v('button.single_add_to_cart_button[name="add-to-cart"]', t).length && (u = parseInt(v('button.single_add_to_cart_button[name="add-to-cart"]', t).val())), d || v.ajax({
                    type: "post",
                    dataType: "json",
                    url: devvn_quickbuy_array.ajaxurl,
                    data: {
                        action: "devvn_apply_coupon",
                        prod_id: u,
                        thisCoupon: e,
                        product_info: c
                    },
                    context: this,
                    beforeSend: function () {
                        d = !0, a.addClass("popup_loading")
                    },
                    success: function (e) {
                        n.show(), i.html("").show(), e.success ? (amout_coupon = e.data.total_discount, i.html(e.data.mess), r.val(amout_coupon), amout_coupon ? (o.show(), s.html(l(amout_coupon.toFixed(0), 0, "", ".") + " " + devvn_quickbuy_array.currency_format)) : o.hide()) : i.html(e.data), p(t), setTimeout(function () {
                            i.html("").hide()
                        }, 2e3), d = !1, a.removeClass("popup_loading")
                    },
                    error: function (e, t, n) {
                        d = !1, a.removeClass("popup_loading")
                    }
                }), !1
            })
        })
    }(jQuery);

//
var arr_selected_swatches = {};

function WGR_after_load_devvn_quick_cart() {
    setTimeout(function () {
        WGR_action_load_devvn_quick_cart();
    }, 600);
}

function WGR_action_load_devvn_quick_cart() {
    // selected thuộc tính theo select của người dùng trước đó
    //console.log(sessionStorage.getItem('arr-elected-swatches'));
    var user_elected_swatches = sessionStorage.getItem('arr-elected-swatches');
    if (user_elected_swatches === null) {
        return false;
    }
    user_elected_swatches = JSON.parse(user_elected_swatches);
    //console.log(user_elected_swatches);

    //
    for (var x in user_elected_swatches) {
        //console.log(x);
        //console.log(user_elected_swatches[x]);
        jQuery('.devvn_prod_variable .ux-swatches[data-attribute_name="' + x + '"] .ux-swatch[data-value="' + user_elected_swatches[x] + '"]').trigger('click');
    }
}

// mỗi lần truy cập là dọn cái session này đi
sessionStorage.removeItem('arr-elected-swatches');
//console.log(sessionStorage.getItem('arr-elected-swatches'));
jQuery('.ux-swatches .ux-swatch').click(function () {
    var a = jQuery(this).parent('.ux-swatches').attr('data-attribute_name') || '';
    //console.log(a);
    var b = jQuery(this).attr('data-value') || '';
    //console.log(b);

    //
    arr_selected_swatches[a] = b;
    //console.log(arr_selected_swatches);

    // lưu dưới dạng ses
    sessionStorage.setItem('arr-elected-swatches', JSON.stringify(arr_selected_swatches));
});