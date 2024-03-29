/* https://jscompress.com/ */
var woof_redirect = "";

function woof_redirect_init() {
    try {
        if (jQuery(".woof").length && void 0 !== jQuery(".woof").val()) return 0 < (woof_redirect = jQuery(".woof").eq(0).data("redirect")).length && (woof_shop_page = woof_current_page_link = woof_redirect), woof_redirect
    } catch (e) {
        console.log(e)
    }
}

function woof_init_orderby() {
    jQuery("form.woocommerce-ordering").life("submit", function() {
        if (!jQuery("#is_woo_shortcode").length) return !1
    }), jQuery("form.woocommerce-ordering select.orderby").life("change", function() {
        if (!jQuery("#is_woo_shortcode").length) return woof_current_values.orderby = jQuery(this).val(), woof_ajax_page_num = 1, woof_submit_link(woof_get_submit_link()), !1
    })
}

function woof_init_reset_button() {
    jQuery(".woof_reset_search_form").life("click", function() {
        if (woof_ajax_page_num = 1, woof_ajax_redraw = 0, woof_is_permalink) woof_current_values = {}, woof_submit_link(woof_get_submit_link().split("page/")[0]);
        else {
            var e = woof_shop_page;
            woof_current_values.hasOwnProperty("page_id") && (e = location.protocol + "//" + location.host + "/?page_id=" + woof_current_values.page_id, woof_current_values = {
                page_id: woof_current_values.page_id
            }, woof_get_submit_link()), woof_submit_link(e), woof_is_ajax && (history.pushState({}, "", e), woof_current_values.hasOwnProperty("page_id") ? woof_current_values = {
                page_id: woof_current_values.page_id
            } : woof_current_values = {})
        }
        return !1
    })
}

function woof_init_pagination() {
    1 === woof_is_ajax && jQuery("a.page-numbers").life("click", function() {
        var e, o, r = jQuery(this).attr("href");
        woof_ajax_first_done ? (void 0 !== (e = r.split("paged="))[1] ? woof_ajax_page_num = parseInt(e[1]) : woof_ajax_page_num = 1, void 0 !== (o = r.split("product-page="))[1] && (woof_ajax_page_num = parseInt(o[1]))) : (void 0 !== (e = r.split("page/"))[1] ? woof_ajax_page_num = parseInt(e[1]) : woof_ajax_page_num = 1, void 0 !== (o = r.split("product-page="))[1] && (woof_ajax_page_num = parseInt(o[1])));
        return woof_submit_link(woof_get_submit_link()), !1
    })
}

function woof_init_search_form() {
    woof_init_checkboxes(), woof_init_mselects(), woof_init_radios(), woof_price_filter_radio_init(), woof_init_selects(), null !== woof_ext_init_functions && jQuery.each(woof_ext_init_functions, function(type, func) {
        eval(func + "()")
    }), jQuery(".woof_submit_search_form").click(function() {
        return woof_ajax_redraw && (woof_ajax_redraw = 0, woof_is_ajax = 0), woof_submit_link(woof_get_submit_link()), !1
    }), jQuery("ul.woof_childs_list").parent("li").addClass("woof_childs_list_li"), woof_remove_class_widget(), woof_checkboxes_slide()
}
jQuery(function(e) {
    jQuery("body").append('<div id="woof_html_buffer" class="woof_info_popup" style="display: none;"></div>'), jQuery.fn.life = function(e, o, r) {
        return jQuery(this.context).on(e, this.selector, o, r), this
    }, jQuery.extend(jQuery.fn, {
        within: function(e) {
            return this.filter(function() {
                return jQuery(this).closest(e).length
            })
        }
    }), 0 < jQuery("#woof_results_by_ajax").length && (woof_is_ajax = 1), woof_autosubmit = parseInt(jQuery(".woof").eq(0).data("autosubmit"), 10), woof_ajax_redraw = parseInt(jQuery(".woof").eq(0).data("ajax-redraw"), 10), woof_ext_init_functions = jQuery.parseJSON(woof_ext_init_functions), woof_init_native_woo_price_filter(), jQuery("body").bind("price_slider_change", function(e, o, r) {
        if (woof_autosubmit && !woof_show_price_search_button && jQuery(".price_slider_wrapper").length < 2) jQuery(".woof .widget_price_filter form").trigger("submit");
        else {
            var t = jQuery(this).find(".price_slider_amount #min_price").val(),
                _ = jQuery(this).find(".price_slider_amount #max_price").val();
            woof_current_values.min_price = t, woof_current_values.max_price = _
        }
    }), jQuery(".woof_price_filter_dropdown").life("change", function() {
        var e = jQuery(this).val();
        if (-1 == parseInt(e, 10)) delete woof_current_values.min_price, delete woof_current_values.max_price;
        else {
            e = e.split("-");
            woof_current_values.min_price = e[0], woof_current_values.max_price = e[1]
        }(woof_autosubmit || 0 == jQuery(this).within(".woof").length) && woof_submit_link(woof_get_submit_link())
    }), woof_recount_text_price_filter(), jQuery(".woof_price_filter_txt").life("change", function() {
        var e = parseInt(jQuery(this).parent().find(".woof_price_filter_txt_from").val(), 10),
            o = parseInt(jQuery(this).parent().find(".woof_price_filter_txt_to").val(), 10);
        o < e || e < 0 ? (delete woof_current_values.min_price, delete woof_current_values.max_price) : ("undefined" != typeof woocs_current_currency && (e = Math.ceil(e / parseFloat(woocs_current_currency.rate)), o = Math.ceil(o / parseFloat(woocs_current_currency.rate))), woof_current_values.min_price = e, woof_current_values.max_price = o), (woof_autosubmit || 0 == jQuery(this).within(".woof").length) && woof_submit_link(woof_get_submit_link())
    }), jQuery(".woof_open_hidden_li_btn").life("click", function() {
        var e = jQuery(this).data("state"),
            o = jQuery(this).data("type");
        return "closed" == e ? (jQuery(this).parents(".woof_list").find(".woof_hidden_term").addClass("woof_hidden_term2"), jQuery(this).parents(".woof_list").find(".woof_hidden_term").removeClass("woof_hidden_term"), "image" == o ? jQuery(this).find("img").attr("src", jQuery(this).data("opened")) : jQuery(this).html(jQuery(this).data("opened")), jQuery(this).data("state", "opened")) : (jQuery(this).parents(".woof_list").find(".woof_hidden_term2").addClass("woof_hidden_term"), jQuery(this).parents(".woof_list").find(".woof_hidden_term2").removeClass("woof_hidden_term2"), "image" == o ? jQuery(this).find("img").attr("src", jQuery(this).data("closed")) : jQuery(this).text(jQuery(this).data("closed")), jQuery(this).data("state", "closed")), !1
    }), woof_open_hidden_li(), jQuery(".widget_rating_filter li.wc-layered-nav-rating a").click(function() {
        var e = jQuery(this).parent().hasClass("chosen"),
            o = woof_parse_url(jQuery(this).attr("href")),
            r = 0;
        if (void 0 !== o.query && -1 !== o.query.indexOf("min_rating")) {
            var t = o.query.split("min_rating=");
            r = parseInt(t[1], 10)
        }
        return jQuery(this).parents("ul").find("li").removeClass("chosen"), e ? delete woof_current_values.min_rating : (woof_current_values.min_rating = r, jQuery(this).parent().addClass("chosen")), woof_submit_link(woof_get_submit_link()), !1
    }), jQuery(".woof_start_filtering_btn").life("click", function() {
        var e = jQuery(this).parents(".woof").data("shortcode");
        jQuery(this).html(woof_lang_loading), jQuery(this).addClass("woof_start_filtering_btn2"), jQuery(this).removeClass("woof_start_filtering_btn");
        var o = {
            action: "woof_draw_products",
            page: 1,
            shortcode: "woof_nothing",
            woof_shortcode: e
        };
        return console.log(o), jQuery.post(woof_ajaxurl, o, function(e) {
            e = jQuery.parseJSON(e), jQuery("div.woof_redraw_zone").replaceWith(jQuery(e.form).find(".woof_redraw_zone")), woof_mass_reinit()
        }), !1
    });
    var i = window.location.href;
    window.onpopstate = function(e) {
        try {
            if (console.log(woof_current_values), Object.keys(woof_current_values).length) {
                var o = i.split("?"),
                    r = "";
                null != o[1] && (r = o[1].split("#"));
                var t = window.location.href.split("?");
                if (null == t[1]) var _ = {
                    0: "",
                    1: ""
                };
                else _ = t[1].split("#");
                return _[0] != r[0] && (woof_show_info_popup(woof_lang_loading), window.location.reload()), !1
            }
        } catch (e) {
            console.log(e)
        }
    }, woof_init_ion_sliders(), woof_init_show_auto_form(), woof_init_hide_auto_form(), woof_remove_empty_elements(), woof_init_search_form(), woof_init_pagination(), woof_init_orderby(), woof_init_reset_button(), woof_init_beauty_scroll(), woof_draw_products_top_panel(), woof_shortcode_observer(), woof_init_tooltip(), woof_is_ajax || woof_redirect_init(), woof_init_toggles()
});
var woof_submit_link_locked = !1;

function woof_submit_link(e) {
    if (!woof_submit_link_locked)
        if (woof_submit_link_locked = !0, woof_show_info_popup(woof_lang_loading), 1 !== woof_is_ajax || woof_ajax_redraw)
            if (woof_ajax_redraw) {
                o = {
                    action: "woof_draw_products",
                    link: e,
                    page: 1,
                    shortcode: "woof_nothing",
                    woof_shortcode: jQuery("div.woof").eq(0).data("shortcode")
                };
                jQuery.post(woof_ajaxurl, o, function(e) {
                    e = jQuery.parseJSON(e), jQuery("div.woof_redraw_zone").replaceWith(jQuery(e.form).find(".woof_redraw_zone")), woof_mass_reinit(), woof_submit_link_locked = !1
                })
            } else window.location = e, woof_show_info_popup(woof_lang_loading);
    else {
        woof_ajax_first_done = !0;
        var o = {
            action: "woof_draw_products",
            link: e,
            page: woof_ajax_page_num,
            shortcode: jQuery("#woof_results_by_ajax").data("shortcode"),
            woof_shortcode: jQuery("div.woof").data("shortcode")
        };
        jQuery.post(woof_ajaxurl, o, function(e) {
            e = jQuery.parseJSON(e), jQuery(".woof_results_by_ajax_shortcode").length ? jQuery("#woof_results_by_ajax").replaceWith(e.products) : jQuery(".woof_shortcode_output").replaceWith(e.products), jQuery("div.woof_redraw_zone").replaceWith(jQuery(e.form).find(".woof_redraw_zone")), woof_draw_products_top_panel(), woof_mass_reinit(), woof_submit_link_locked = !1, jQuery.each(jQuery("#woof_results_by_ajax"), function(e, o) {
                0 != e && jQuery(o).removeAttr("id")
            }), woof_infinite(), woof_js_after_ajax_done(), woof_change_link_addtocart(), woof_init_tooltip()
            jQuery(".woocommerce-ordering .orderby").addClass('input-select justselect').wrapAll('<div class="selectric-wrapper selectric-input-select selectric-responsive"><div class="justwrap"></div></div>');
        })
    }
}

function woof_remove_empty_elements() {
    jQuery.each(jQuery(".woof_container select"), function(e, o) {
        0 === jQuery(o).find("option").length && jQuery(o).parents(".woof_container").remove()
    }), jQuery.each(jQuery("ul.woof_list"), function(e, o) {
        0 === jQuery(o).find("li").length && jQuery(o).parents(".woof_container").remove()
    })
}

function woof_get_submit_link() {
    if (woof_is_ajax && (woof_current_values.page = woof_ajax_page_num), 0 < Object.keys(woof_current_values).length && jQuery.each(woof_current_values, function(e, o) {
            e == swoof_search_slug && delete woof_current_values[e], "s" == e && delete woof_current_values[e], "product" == e && delete woof_current_values[e], "really_curr_tax" == e && delete woof_current_values[e]
        }), 2 === Object.keys(woof_current_values).length && "min_price" in woof_current_values && "max_price" in woof_current_values) {
        var e = woof_current_page_link + "?min_price=" + woof_current_values.min_price + "&max_price=" + woof_current_values.max_price;
        return woof_is_ajax && history.pushState({}, "", e), e
    }
    if (0 === Object.keys(woof_current_values).length) return woof_is_ajax && history.pushState({}, "", woof_current_page_link), woof_current_page_link;
    0 < Object.keys(woof_really_curr_tax).length && (woof_current_values.really_curr_tax = woof_really_curr_tax.term_id + "-" + woof_really_curr_tax.taxonomy);
    var r = woof_current_page_link + "?" + swoof_search_slug + "=1";
    woof_is_permalink || (0 < woof_redirect.length ? (r = woof_redirect + "?" + swoof_search_slug + "=1", woof_current_values.hasOwnProperty("page_id") && delete woof_current_values.page_id) : r = location.protocol + "//" + location.host + "?" + swoof_search_slug + "=1");
    var t = ["path"];
    return 0 < Object.keys(woof_current_values).length && jQuery.each(woof_current_values, function(e, o) {
        "page" == e && woof_is_ajax && (e = "paged"), void 0 !== o && (0 < o.length || "number" == typeof o) && -1 == jQuery.inArray(e, t) && (r = r + "&" + e + "=" + o)
    }), r = r.replace(new RegExp(/page\/(\d+)\//), ""), woof_is_ajax && history.pushState({}, "", r), r
}

function woof_show_info_popup(e) {
    if ("default" == woof_overlay_skin) jQuery("#woof_html_buffer").text(e), jQuery("#woof_html_buffer").fadeTo(200, .9);
    else switch (woof_overlay_skin) {
        case "loading-balls":
        case "loading-bars":
        case "loading-bubbles":
        case "loading-cubes":
        case "loading-cylon":
        case "loading-spin":
        case "loading-spinning-bubbles":
        case "loading-spokes":
            jQuery("body").plainOverlay("show", {
                progress: function() {
                    return jQuery('<div id="woof_svg_load_container"><img style="height: 100%;width: 100%" src="' + woof_link + "img/loading-master/" + woof_overlay_skin + '.svg" alt=""></div>')
                }
            });
            break;
        default:
            jQuery("body").plainOverlay("show", {
                duration: -1
            })
    }
}

function woof_hide_info_popup() {
    "default" == woof_overlay_skin ? window.setTimeout(function() {
        jQuery("#woof_html_buffer").fadeOut(400)
    }, 200) : jQuery("body").plainOverlay("hide")
}

function woof_draw_products_top_panel() {
    woof_is_ajax && jQuery("#woof_results_by_ajax").prev(".woof_products_top_panel").remove();
    var o = jQuery(".woof_products_top_panel");
    if (o.html(""), 0 < Object.keys(woof_current_values).length) {
        o.show(), o.html("<ul></ul>");
        var r = !1;
        jQuery.each(woof_current_values, function(i, e) {
            -1 != jQuery.inArray(i, woof_accept_array) && ("min_price" != i && "max_price" != i || !r) && ("min_price" != i && "max_price" != i || r || (r = !0, i = "price", e = woof_lang_pricerange), (e = e.toString().trim()).search(",") && (e = e.split(",")), jQuery.each(e, function(e, r) {
                if ("page" != i && "post_type" != i) {
                    var t = r;
                    if ("orderby" == i) t = void 0 !== woof_lang[r] ? woof_lang.orderby + ": " + woof_lang[r] : woof_lang.orderby + ": " + r;
                    else if ("perpage" == i) t = woof_lang.perpage;
                    else if ("price" == i) t = woof_lang.pricerange;
                    else {
                        var _ = !1;
                        if (0 < Object.keys(woof_lang_custom).length && jQuery.each(woof_lang_custom, function(e, o) {
                                e == i && (_ = !0, t = o, "woof_sku" == i && (t += " " + r))
                            }), !_) {
                            try {
                                t = jQuery("input[data-anchor='woof_n_" + i + "_" + r + "']").val()
                            } catch (e) {
                                console.log(e)
                            }
                            void 0 === t && (t = r)
                        }
                    }
                    o.find("ul").append(jQuery("<li>").append(jQuery("<a>").attr("href", r).attr("data-tax", i).append(jQuery("<span>").attr("class", "woof_remove_ppi").append(t))))
                }
            }))
        })
    }
    0 != jQuery(o).find("li").length && jQuery(".woof_products_top_panel").length || o.hide(), jQuery(".woof_remove_ppi").parent().click(function() {
        var e = jQuery(this).data("tax"),
            r = jQuery(this).attr("href");
        if ("price" != e) {
            values = woof_current_values[e], values = values.split(",");
            var t = [];
            jQuery.each(values, function(e, o) {
                o != r && t.push(o)
            }), values = t, values.length ? woof_current_values[e] = values.join(",") : delete woof_current_values[e]
        } else delete woof_current_values.min_price, delete woof_current_values.max_price;
        return woof_ajax_page_num = 1, woof_submit_link(woof_get_submit_link()), jQuery(".woof_products_top_panel").find("[data-tax='" + e + "'][href='" + r + "']").hide(333), !1
    })
}

function woof_shortcode_observer() {
    var e = !0;
    (jQuery(".woof_shortcode_output").length || jQuery(".woocommerce .products").length && !jQuery(".single-product").length) && (e = !1), jQuery(".woocommerce .woocommerce-info").length && (e = !1), "undefined" != typeof woof_not_redirect && 1 == woof_not_redirect && (e = !1), e || (woof_current_page_link = location.protocol + "//" + location.host + location.pathname), jQuery("#woof_results_by_ajax").length && (woof_is_ajax = 1)
}

function woof_init_beauty_scroll() {
    if (woof_use_beauty_scroll) try {
        var e = ".woof_section_scrolled, .woof_sid_auto_shortcode .woof_container_radio .woof_block_html_items, .woof_sid_auto_shortcode .woof_container_checkbox .woof_block_html_items, .woof_sid_auto_shortcode .woof_container_label .woof_block_html_items";
        jQuery("" + e).mCustomScrollbar("destroy"), jQuery("" + e).mCustomScrollbar({
            scrollButtons: {
                enable: !0
            },
            advanced: {
                updateOnContentResize: !0,
                updateOnBrowserResize: !0
            },
            theme: "dark-2",
            horizontalScroll: !1,
            mouseWheel: !0,
            scrollType: "pixels",
            contentTouchScroll: !0
        })
    } catch (e) {
        console.log(e)
    }
}

function woof_remove_class_widget() {
    jQuery(".woof_container_inner").find(".widget").removeClass("widget")
}

function woof_init_show_auto_form() {
    jQuery(".woof_show_auto_form").unbind("click"), jQuery(".woof_show_auto_form").click(function() {
        return jQuery(this).addClass("woof_hide_auto_form").removeClass("woof_show_auto_form"), jQuery(".woof_auto_show").show().animate({
            height: jQuery(".woof_auto_show_indent").height() + 20 + "px",
            opacity: 1
        }, 377, function() {
            woof_init_hide_auto_form(), jQuery(".woof_auto_show").removeClass("woof_overflow_hidden"), jQuery(".woof_auto_show_indent").removeClass("woof_overflow_hidden"), jQuery(".woof_auto_show").height("auto")
        }), !1
    })
}

function woof_init_hide_auto_form() {
    jQuery(".woof_hide_auto_form").unbind("click"), jQuery(".woof_hide_auto_form").click(function() {
        return jQuery(this).addClass("woof_show_auto_form").removeClass("woof_hide_auto_form"), jQuery(".woof_auto_show").show().animate({
            height: "1px",
            opacity: 0
        }, 377, function() {
            jQuery(".woof_auto_show").addClass("woof_overflow_hidden"), jQuery(".woof_auto_show_indent").addClass("woof_overflow_hidden"), woof_init_show_auto_form()
        }), !1
    })
}

function woof_checkboxes_slide() {
    if (1 == woof_checkboxes_slide_flag) {
        var e = jQuery("ul.woof_childs_list");
        e.length && (jQuery.each(e, function(e, o) {
            if (!jQuery(o).parents(".woof_no_close_childs").length) {
                var r = "woof_is_closed";
                if (woof_supports_html5_storage()) {
                    var t = localStorage.getItem(jQuery(o).closest("li").find("label").first().text());
                    if (t && "woof_is_opened" == t) {
                        r = "woof_is_opened";
                        jQuery(o).show()
                    }
                    jQuery(o).before('<a href="javascript:void(0);" class="woof_childs_list_opener"><span class="' + r + '"></span></a>')
                } else jQuery(o).find("input[type=checkbox],input[type=radio]").is(":checked") && (jQuery(o).show(), r = "woof_is_opened"), jQuery(o).before('<a href="javascript:void(0);" class="woof_childs_list_opener"><span class="' + r + '"></span></a>')
            }
        }), jQuery.each(jQuery("a.woof_childs_list_opener"), function(e, o) {
            jQuery(o).click(function() {
                var e = jQuery(this).find("span");
                if (e.hasClass("woof_is_closed") ? (jQuery(this).parent().find("ul.woof_childs_list").first().show(333), e.removeClass("woof_is_closed"), e.addClass("woof_is_opened")) : (jQuery(this).parent().find("ul.woof_childs_list").first().hide(333), e.removeClass("woof_is_opened"), e.addClass("woof_is_closed")), woof_supports_html5_storage()) {
                    var o = jQuery(this).closest("li").find("label").first().text(),
                        r = jQuery(this).children("span").attr("class");
                    localStorage.setItem(o, r)
                }
                return !1
            })
        }))
    }
}

function woof_init_ion_sliders() {
    jQuery.each(jQuery(".woof_range_slider"), function(e, r) {
        try {
            jQuery(r).ionRangeSlider({
                min: jQuery(r).data("min"),
                max: jQuery(r).data("max"),
                from: jQuery(r).data("min-now"),
                to: jQuery(r).data("max-now"),
                type: "double",
                prefix: jQuery(r).data("slider-prefix"),
                postfix: jQuery(r).data("slider-postfix"),
                prettify: !0,
                hideMinMax: !1,
                hideFromTo: !1,
                grid: !0,
                step: jQuery(r).data("step"),
                onFinish: function(e) {
                    var o = jQuery(r).data("taxes");
                    return woof_current_values.min_price = parseInt(e.from, 10) / o, woof_current_values.max_price = parseInt(e.to, 10) / o, "undefined" != typeof woocs_current_currency && (woof_current_values.min_price = Math.ceil(woof_current_values.min_price / parseFloat(woocs_current_currency.rate)), woof_current_values.max_price = Math.ceil(woof_current_values.max_price / parseFloat(woocs_current_currency.rate))), woof_ajax_page_num = 1, (woof_autosubmit || 0 == jQuery(r).within(".woof").length) && woof_submit_link(woof_get_submit_link()), !1
                }
            })
        } catch (e) {}
    })
}

function woof_init_native_woo_price_filter() {
    jQuery(".widget_price_filter form").unbind("submit"), jQuery(".widget_price_filter form").submit(function() {
        var e = jQuery(this).find(".price_slider_amount #min_price").val(),
            o = jQuery(this).find(".price_slider_amount #max_price").val();
        return woof_current_values.min_price = e, woof_current_values.max_price = o, woof_ajax_page_num = 1, (woof_autosubmit || 0 == jQuery(input).within(".woof").length) && woof_submit_link(woof_get_submit_link()), !1
    })
}

function woof_reinit_native_woo_price_filter() {
    if ("undefined" == typeof woocommerce_price_slider_params) return !1;
    jQuery("input#min_price, input#max_price").hide(), jQuery(".price_slider, .price_label").show();
    var e = jQuery(".price_slider_amount #min_price").data("min"),
        o = jQuery(".price_slider_amount #max_price").data("max"),
        r = parseInt(e, 10),
        t = parseInt(o, 10);
    woof_current_values.hasOwnProperty("min_price") ? (r = parseInt(woof_current_values.min_price, 10), t = parseInt(woof_current_values.max_price, 10)) : (woocommerce_price_slider_params.min_price && (r = parseInt(woocommerce_price_slider_params.min_price, 10)), woocommerce_price_slider_params.max_price && (t = parseInt(woocommerce_price_slider_params.max_price, 10)));
    var i = woocommerce_price_slider_params.currency_symbol;
    null == typeof i && (i = woocommerce_price_slider_params.currency_format_symbol), jQuery(document.body).bind("price_slider_create price_slider_slide", function(e, o, r) {
        if ("undefined" != typeof woocs_current_currency) {
            var t = o,
                _ = r;
            1 !== woocs_current_currency.rate && (t = Math.ceil(t * parseFloat(woocs_current_currency.rate)), _ = Math.ceil(_ * parseFloat(woocs_current_currency.rate))), t = woof_front_number_format(t, 2, ".", ","), _ = woof_front_number_format(_, 2, ".", ","), (jQuery.inArray(woocs_current_currency.name, woocs_array_no_cents) || 1 == woocs_current_currency.hide_cents) && (t = t.replace(".00", ""), _ = _.replace(".00", "")), "left" === woocs_current_currency.position ? (jQuery(".price_slider_amount span.from").html(i + t), jQuery(".price_slider_amount span.to").html(i + _)) : "left_space" === woocs_current_currency.position ? (jQuery(".price_slider_amount span.from").html(i + " " + t), jQuery(".price_slider_amount span.to").html(i + " " + _)) : "right" === woocs_current_currency.position ? (jQuery(".price_slider_amount span.from").html(t + i), jQuery(".price_slider_amount span.to").html(_ + i)) : "right_space" === woocs_current_currency.position && (jQuery(".price_slider_amount span.from").html(t + " " + i), jQuery(".price_slider_amount span.to").html(_ + " " + i))
        } else "left" === woocommerce_price_slider_params.currency_pos ? (jQuery(".price_slider_amount span.from").html(i + o), jQuery(".price_slider_amount span.to").html(i + r)) : "left_space" === woocommerce_price_slider_params.currency_pos ? (jQuery(".price_slider_amount span.from").html(i + " " + o), jQuery(".price_slider_amount span.to").html(i + " " + r)) : "right" === woocommerce_price_slider_params.currency_pos ? (jQuery(".price_slider_amount span.from").html(o + i), jQuery(".price_slider_amount span.to").html(r + i)) : "right_space" === woocommerce_price_slider_params.currency_pos && (jQuery(".price_slider_amount span.from").html(o + " " + i), jQuery(".price_slider_amount span.to").html(r + " " + i));
        jQuery(document.body).trigger("price_slider_updated", [o, r])
    }), jQuery(".price_slider").slider({
        range: !0,
        animate: !0,
        min: e,
        max: o,
        values: [r, t],
        create: function() {
            jQuery(".price_slider_amount #min_price").val(r), jQuery(".price_slider_amount #max_price").val(t), jQuery(document.body).trigger("price_slider_create", [r, t])
        },
        slide: function(e, o) {
            jQuery("input#min_price").val(o.values[0]), jQuery("input#max_price").val(o.values[1]), jQuery(document.body).trigger("price_slider_slide", [o.values[0], o.values[1]])
        },
        change: function(e, o) {
            jQuery(document.body).trigger("price_slider_change", [o.values[0], o.values[1]])
        }
    }), woof_init_native_woo_price_filter()
}

function woof_mass_reinit() {
    woof_remove_empty_elements(), woof_open_hidden_li(), woof_init_search_form(), woof_hide_info_popup(), woof_init_beauty_scroll(), woof_init_ion_sliders(), woof_reinit_native_woo_price_filter(), woof_recount_text_price_filter(), woof_draw_products_top_panel()
}

function woof_recount_text_price_filter() {
    "undefined" != typeof woocs_current_currency && jQuery.each(jQuery(".woof_price_filter_txt_from, .woof_price_filter_txt_to"), function(e, o) {
        jQuery(this).val(Math.ceil(jQuery(this).data("value")))
    })
}

function woof_init_toggles() {
    jQuery(".woof_front_toggle").life("click", function() {
        return "opened" == jQuery(this).data("condition") ? (jQuery(this).removeClass("woof_front_toggle_opened"), jQuery(this).addClass("woof_front_toggle_closed"), jQuery(this).data("condition", "closed"), "text" == woof_toggle_type ? jQuery(this).text(woof_toggle_closed_text) : jQuery(this).find("img").prop("src", woof_toggle_closed_image)) : (jQuery(this).addClass("woof_front_toggle_opened"), jQuery(this).removeClass("woof_front_toggle_closed"), jQuery(this).data("condition", "opened"), "text" == woof_toggle_type ? jQuery(this).text(woof_toggle_opened_text) : jQuery(this).find("img").prop("src", woof_toggle_opened_image)), jQuery(this).parents(".woof_container_inner").find(".woof_block_html_items").toggle(500), !1
    })
}

function woof_open_hidden_li() {
    0 < jQuery(".woof_open_hidden_li_btn").length && jQuery.each(jQuery(".woof_open_hidden_li_btn"), function(e, o) {
        jQuery(o).parents("ul").find("li.woof_hidden_term input[type=checkbox],li.woof_hidden_term input[type=radio]").is(":checked") && jQuery(o).trigger("click")
    })
}

function $_woof_GET(e, o) {
    o = o || window.location.search;
    var r = new RegExp("&" + e + "=([^&]*)", "i");
    return o = (o = o.replace(/^\?/, "&").match(r)) ? o[1] : ""
}

function woof_parse_url(e) {
    var o = RegExp("^(([^:/?#]+):)?(//([^/?#]*))?([^?#]*)(\\?([^#]*))?(#(.*))?"),
        r = e.match(o);
    return {
        scheme: r[2],
        authority: r[4],
        path: r[5],
        query: r[7],
        fragment: r[9]
    }
}

function woof_price_filter_radio_init() {
    "none" != icheck_skin ? (jQuery(".woof_price_filter_radio").iCheck("destroy"), jQuery(".woof_price_filter_radio").iCheck({
        radioClass: "iradio_" + icheck_skin.skin + "-" + icheck_skin.color
    }), jQuery(".woof_price_filter_radio").siblings("div").removeClass("checked"), jQuery(".woof_price_filter_radio").unbind("ifChecked"), jQuery(".woof_price_filter_radio").on("ifChecked", function(e) {
        jQuery(this).attr("checked", !0), jQuery(".woof_radio_price_reset").removeClass("woof_radio_term_reset_visible"), jQuery(this).parents(".woof_list").find(".woof_radio_price_reset").removeClass("woof_radio_term_reset_visible"), jQuery(this).parents(".woof_list").find(".woof_radio_price_reset").hide(), jQuery(this).parents("li").eq(0).find(".woof_radio_price_reset").eq(0).addClass("woof_radio_term_reset_visible");
        var o = jQuery(this).val();
        if (-1 == parseInt(o, 10)) delete woof_current_values.min_price, delete woof_current_values.max_price, jQuery(this).removeAttr("checked"), jQuery(this).siblings(".woof_radio_price_reset").removeClass("woof_radio_term_reset_visible");
        else {
            o = o.split("-");
            woof_current_values.min_price = o[0], woof_current_values.max_price = o[1], jQuery(this).siblings(".woof_radio_price_reset").addClass("woof_radio_term_reset_visible"), jQuery(this).attr("checked", !0)
        }(woof_autosubmit || 0 == jQuery(this).within(".woof").length) && woof_submit_link(woof_get_submit_link())
    })) : jQuery(".woof_price_filter_radio").life("change", function() {
        var e = jQuery(this).val();
        if (jQuery(".woof_radio_price_reset").removeClass("woof_radio_term_reset_visible"), -1 == parseInt(e, 10)) delete woof_current_values.min_price, delete woof_current_values.max_price, jQuery(this).removeAttr("checked"), jQuery(this).siblings(".woof_radio_price_reset").removeClass("woof_radio_term_reset_visible");
        else {
            e = e.split("-");
            woof_current_values.min_price = e[0], woof_current_values.max_price = e[1], jQuery(this).siblings(".woof_radio_price_reset").addClass("woof_radio_term_reset_visible"), jQuery(this).attr("checked", !0)
        }(woof_autosubmit || 0 == jQuery(this).within(".woof").length) && woof_submit_link(woof_get_submit_link())
    }), jQuery(".woof_radio_price_reset").click(function() {
        return delete woof_current_values.min_price, delete woof_current_values.max_price, jQuery(this).siblings("div").removeClass("checked"), jQuery(this).parents(".woof_list").find("input[type=radio]").removeAttr("checked"), jQuery(this).removeClass("woof_radio_term_reset_visible"), woof_autosubmit && woof_submit_link(woof_get_submit_link()), !1
    })
}

function woof_serialize(e) {
    for (var o, r, t = decodeURI(e).split("&"), _ = {}, i = 0, n = t.length; i < n; i++)
        if ((r = (o = t[i].split("="))[0]).indexOf("[]") == r.length - 2) {
            var a = r.substring(0, r.length - 2);
            void 0 === _[a] && (_[a] = []), _[a].push(o[1])
        } else _[r] = o[1];
    return _
}

function woof_infinite() {
    if ("undefined" != typeof yith_infs) {
        var e = {
                nextSelector: ".woocommerce-pagination li .next",
                navSelector: yith_infs.navSelector,
                itemSelector: yith_infs.itemSelector,
                contentSelector: yith_infs.contentSelector,
                loader: '<img src="' + yith_infs.loader + '">',
                is_shop: yith_infs.shop
            },
            o = window.location.href.split("?"),
            r = "";
        if (null != o[1]) {
            var t = woof_serialize(o[1]);
            delete t.paged, r = decodeURIComponent(jQuery.param(t))
        }
        var _ = jQuery(".woocommerce-pagination li .next").attr("href");
        null == _ && (_ = o + "page/1/"), console.log(_);
        var i = _.split("?"),
            n = "";
        if (null != i[1]) {
            var a = woof_serialize(i[1]);
            null != a.paged && (n = "page/" + a.paged + "/")
        }
        _ = o[0] + n + "?" + r, jQuery(".woocommerce-pagination li .next").attr("href", _), jQuery(window).unbind("yith_infs_start"), jQuery(yith_infs.contentSelector).yit_infinitescroll(e)
    }
}

function woof_change_link_addtocart() {
    woof_is_ajax && jQuery(".add_to_cart_button").each(function(e, o) {
        var r = jQuery(o).attr("href"),
            t = r.split("?"),
            _ = window.location.href.split("?");
        null != t[1] && (r = _[0] + "?" + t[1], jQuery(o).attr("href", r))
    })
}

function woof_front_number_format(e, o, r, t) {
    e = (e + "").replace(/[^0-9+\-Ee.]/g, "");
    var _, i, n, a = isFinite(+e) ? +e : 0,
        s = isFinite(+o) ? Math.abs(o) : 0,
        c = void 0 === t ? "," : t,
        u = void 0 === r ? "." : r,
        f = "";
    return 3 < (f = (s ? (_ = a, i = s, n = Math.pow(10, i), "" + (Math.round(_ * n) / n).toFixed(i)) : "" + Math.round(a)).split("."))[0].length && (f[0] = f[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, c)), (f[1] || "").length < s && (f[1] = f[1] || "", f[1] += new Array(s - f[1].length + 1).join("0")), f.join(u)
}

function woof_supports_html5_storage() {
    try {
        return "localStorage" in window && null !== window.localStorage
    } catch (e) {
        return !1
    }
}

function woof_init_tooltip() {
    jQuery(".woof_tooltip_header").tooltipster({
        theme: "tooltipster-noir",
        side: "right"
    })
}

function woof_init_checkboxes() {
    "none" != icheck_skin ? (jQuery(".woof_checkbox_term").iCheck("destroy"), jQuery(".woof_checkbox_term").iCheck({
        checkboxClass: "icheckbox_" + icheck_skin.skin + "-" + icheck_skin.color
    }), jQuery(".woof_checkbox_term").unbind("ifChecked"), jQuery(".woof_checkbox_term").on("ifChecked", function(e) {
        jQuery(this).attr("checked", !0), jQuery(".woof_select_radio_check input").attr("disabled", "disabled"), woof_checkbox_process_data(this, !0)
    }), jQuery(".woof_checkbox_term").unbind("ifUnchecked"), jQuery(".woof_checkbox_term").on("ifUnchecked", function(e) {
        jQuery(this).attr("checked", !1), woof_checkbox_process_data(this, !1)
    }), jQuery(".woof_checkbox_label").unbind(), jQuery("label.woof_checkbox_label").click(function() {
        if (jQuery(this).prev().find(".woof_checkbox_term").is(":disabled")) return !1;
        jQuery(this).prev().find(".woof_checkbox_term").is(":checked") ? (jQuery(this).prev().find(".woof_checkbox_term").trigger("ifUnchecked"), jQuery(this).prev().removeClass("checked")) : (jQuery(this).prev().find(".woof_checkbox_term").trigger("ifChecked"), jQuery(this).prev().addClass("checked"))
    })) : jQuery(".woof_checkbox_term").on("change", function(e) {
        jQuery(this).is(":checked") ? (jQuery(this).attr("checked", !0), woof_checkbox_process_data(this, !0)) : (jQuery(this).attr("checked", !1), woof_checkbox_process_data(this, !1))
    })
}

function woof_checkbox_process_data(e, o) {
    var r = jQuery(e).data("tax"),
        t = jQuery(e).attr("name");
    woof_checkbox_direct_search(jQuery(e).data("term-id"), t, r, o)
}

function woof_checkbox_direct_search(e, r, o, t) {
    var _ = "",
        i = !0;
    if (t) o in woof_current_values ? woof_current_values[o] = woof_current_values[o] + "," + r : woof_current_values[o] = r, i = !0;
    else {
        _ = (_ = woof_current_values[o]).split(",");
        var n = [];
        jQuery.each(_, function(e, o) {
            o != r && n.push(o)
        }), (_ = n).length ? woof_current_values[o] = _.join(",") : delete woof_current_values[o], i = !1
    }
    jQuery(".woof_checkbox_term_" + e).attr("checked", i), woof_ajax_page_num = 1, woof_autosubmit && woof_submit_link(woof_get_submit_link())
}

function woof_init_mselects() {
    try {
        jQuery("select.woof_mselect").chosen()
    } catch (e) {}
    jQuery(".woof_mselect").change(function(e) {
        var o = jQuery(this).val(),
            r = jQuery(this).attr("name");
        if (is_woof_use_chosen) {
            var t = jQuery(this).chosen().val();
            jQuery(".woof_mselect[name=" + r + "] option:selected").removeAttr("selected"), jQuery(".woof_mselect[name=" + r + "] option").each(function(e, o) {
                var r = jQuery(this).val(); - 1 !== jQuery.inArray(r, t) && jQuery(this).prop("selected", !0)
            })
        }
        return woof_mselect_direct_search(r, o), !0
    })
}

function woof_mselect_direct_search(e, o) {
    var r = [];
    jQuery(".woof_mselect[name=" + e + "] option:selected").each(function(e, o) {
        r.push(jQuery(this).val())
    }), (r = (r = r.filter(function(e, o) {
        return r.indexOf(e) == o
    })).join(",")).length ? woof_current_values[e] = r : delete woof_current_values[e], woof_ajax_page_num = 1, woof_autosubmit && woof_submit_link(woof_get_submit_link())
}

function woof_init_radios() {
    "none" != icheck_skin ? (jQuery(".woof_radio_term").iCheck("destroy"), jQuery(".woof_radio_term").iCheck({
        radioClass: "iradio_" + icheck_skin.skin + "-" + icheck_skin.color
    }), jQuery(".woof_radio_term").unbind("ifChecked"), jQuery(".woof_radio_term").on("ifChecked", function(e) {
        jQuery(this).attr("checked", !0), jQuery(this).parents(".woof_list").find(".woof_radio_term_reset").removeClass("woof_radio_term_reset_visible"), jQuery(this).parents(".woof_list").find(".woof_radio_term_reset").hide(), jQuery(this).parents("li").eq(0).find(".woof_radio_term_reset").eq(0).addClass("woof_radio_term_reset_visible");
        var o = jQuery(this).data("slug"),
            r = jQuery(this).attr("name");
        woof_radio_direct_search(jQuery(this).data("term-id"), r, o)
    })) : jQuery(".woof_radio_term").on("change", function(e) {
        jQuery(this).attr("checked", !0);
        var o = jQuery(this).data("slug"),
            r = jQuery(this).attr("name");
        woof_radio_direct_search(jQuery(this).data("term-id"), r, o)
    }), jQuery(".woof_radio_term_reset").click(function() {
        return woof_radio_direct_search(jQuery(this).data("term-id"), jQuery(this).attr("data-name"), 0), jQuery(this).parents(".woof_list").find(".checked").removeClass("checked"), jQuery(this).parents(".woof_list").find("input[type=radio]").removeAttr("checked"), jQuery(this).removeClass("woof_radio_term_reset_visible"), !1
    })
}

function woof_radio_direct_search(e, r, o) {
    jQuery.each(woof_current_values, function(e, o) {
        e != r || delete woof_current_values[r]
    }), 0 != o ? (woof_current_values[r] = o, jQuery("a.woof_radio_term_reset_" + e).hide(), jQuery("woof_radio_term_" + e).filter(":checked").parents("li").find("a.woof_radio_term_reset").show(), jQuery("woof_radio_term_" + e).parents("ul.woof_list").find("label").css({
        fontWeight: "normal"
    }), jQuery("woof_radio_term_" + e).filter(":checked").parents("li").find("label.woof_radio_label_" + o).css({
        fontWeight: "bold"
    })) : (jQuery("a.woof_radio_term_reset_" + e).hide(), jQuery("woof_radio_term_" + e).attr("checked", !1), jQuery("woof_radio_term_" + e).parent().removeClass("checked"), jQuery("woof_radio_term_" + e).parents("ul.woof_list").find("label").css({
        fontWeight: "normal"
    })), woof_ajax_page_num = 1, woof_autosubmit && woof_submit_link(woof_get_submit_link())
}

function woof_init_selects() {
    if (is_woof_use_chosen) try {
        jQuery("select.woof_select, select.woof_price_filter_dropdown").chosen()
    } catch (e) {}
    jQuery(".woof_select").change(function() {
        var e = jQuery(this).val();
        woof_select_direct_search(this, jQuery(this).attr("name"), e)
    })
}

function woof_select_direct_search(e, r, o) {
    jQuery.each(woof_current_values, function(e, o) {
        e != r || delete woof_current_values[r]
    }), 0 != o && (woof_current_values[r] = o), woof_ajax_page_num = 1, (woof_autosubmit || 0 == jQuery(e).within(".woof").length) && woof_submit_link(woof_get_submit_link())
}