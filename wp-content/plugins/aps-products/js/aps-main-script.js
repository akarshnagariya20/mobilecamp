(function($) {
    $(window).on("load scroll rating", function() {
        // animate rating bars
        $('[data-bar="true"]').each(function() {
            $(this).apsAnimateBar(3000);
        });
    });

    // aps tooltip function
    $(".aps-tooltip").hover(function() {
        info = $(this).next(".aps-tooltip-data").html();
        $("body").append('<span class="aps-tooltip-display">' + info + '</span>').show(300);
        container = $(".aps-tooltip-display");
        $(document).on("mousemove", function(e) {
            var relY = e.pageY + 20,
                relX = e.pageX + 15;
            container.css({"top":relY, "left":relX});
        });
    }, function() {
        container.hide(50, function() {
            $(this).remove();
        });
    });

    // check element's visibility
    $.fn.apsIsVisible = function() {
        var win = $(window);
        viewport = {
            top : win.scrollTop(),
            left : win.scrollLeft()
        };
        viewport.right = viewport.left + win.width();
        viewport.bottom = viewport.top + win.height();

        if (this.is(":visible")) {
            var bounds = this.offset();
            bounds.right = bounds.left + this.outerWidth();
            bounds.bottom = bounds.top + this.outerHeight();

            return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));
        }
    }

    // animate rating bar
    $.fn.apsAnimateBar = function(dur) {
        var elnum = this.find('[data-type="num"]'),
            elbar = this.find('[data-type="bar"]'),
            rating = this.data("rating"),
            sPoint = { num: 0, wid: 0 },
            ePoint = { num: rating, wid: rating * 10 };
        if (elbar.apsIsVisible( true ) && !this.hasClass("aps-animated")) {
            this.addClass("aps-animated");
            $(sPoint).animate(ePoint, {
                duration: dur,
                step: function() {
                    elnum.html(Number(this.num.toFixed(1)));
                    elbar.css("width", this.wid +"%");
                }
            });
        }
    }

    $.cookie = function (key, value, options) {
        // key and value given, set cookie...
        if (arguments.length > 1 && (value === null || typeof value !== "object")) {
            options = jQuery.extend({}, options);

            if (value === null) {
                options.expires = -1;
            }

            if (typeof options.expires === 'number') {
                var days = options.expires, t = options.expires = new Date();
                t.setDate(t.getDate() + days);
            }

            return (document.cookie = [
                encodeURIComponent(key), '=',
                options.raw ? String(value) : encodeURIComponent(String(value)),
                options.expires ? '; expires=' + options.expires.toUTCString() : '',
                options.path ? '; path=' + options.path : '',
                options.domain ? '; domain=' + options.domain : '',
                options.secure ? '; secure' : ''
            ].join(''));
        }

        // key and possibly options given, get cookie...
        options = value || {};
        var result, decode = options.raw ? function (s) { return s; } : decodeURIComponent;
        return (result = new RegExp('(?:^|; )' + encodeURIComponent(key) + '=([^;]*)').exec(document.cookie)) ? decode(result[1]) : null;
    }

    // add to compare
    $(document).on("click", "a.aps-add-compare", function(e) {
        var my_compare_val = $('a.aps-compare span').text();
        var my_compare_val1 = my_compare_val.substring(1,2);
        var my_compare_val2 = parseInt(my_compare_val1);
        if(my_compare_val2 == 5){

            alert('Sorry not allowed.');
        }else{
            var value = $(this).data("pid").toString(),
                msg = $(this).data("msg"),
                name = "aps_comp",
                comp = $.cookie(name);

            if (comp) {
                var comp_arr = comp.split("-"),
                    found = $.inArray(value, comp_arr),
                    comp_num = comp_arr.length + 1;
                if (found < 0) {
                    value = comp + "-" + value;
                    $.cookie(name, value, {expires: 7, path: "/"});
                    $("a.aps-compare span").html("(" + comp_num + ")");
                }
            } else {
                $.cookie(name, value, {expires: 7, path: "/"});
            }
            aps_response_msg("success", msg, true);
            e.preventDefault();
        }
    });

    // brands dropdown
    $(".aps-dropdown").hover(function() {
        $(this).find("ul").stop().slideDown();
    }, function() {
        $(this).find("ul").stop().slideUp();
    });

    // display switching (list <> grid)
    $(".aps-display-controls li a").click(function(e) {
        var elmList = $(".aps-products"),
            gridClass = "aps-products-grid",
            listClass = "aps-products-list";
        $(".aps-display-controls li a").removeClass("selected");
        $(this).addClass("selected");
        if ($(this).hasClass("aps-display-list")) {
            elmList.removeClass(gridClass).addClass(listClass);
            $.cookie("aps_display", "list", {expires: 30, path: "/"});
        } else {
            elmList.removeClass(listClass).addClass(gridClass);
            $.cookie("aps_display", "grid", {expires: 30, path: "/"});
        }
        e.preventDefault();
    });

    // remove from compare
    $(".aps-remove-compare").click(function() {
        var removeBt = $(this),
            pid = removeBt.data("pid").toString(),
            cName = "aps_comp",
            compList = $.cookie(cName).split("-");
        compList.splice($.inArray(pid, compList), 1);
        newVal = compList.join("-");
        $.cookie(cName, newVal, {expires: 7, path: "/"});
        location.reload();
    });

    // range input bars
    $("[data-slider]").each(function() {
        $(this).after('<span class="aps-range-output"></span>');
    }).bind("slider:ready slider:changed", function(event, data) {
        $(this).nextAll(".aps-range-output:first").html(data.value.toFixed(0));
        var totalSum = 0, inputs = 0;
        $("[data-slider]").each(function() {
            totalSum += Number($(this).val());
            inputs++
        });
        totalRating = totalSum / inputs;
        $(".aps-total-score").html(totalRating);
    });

    // submit review data using ajax
    $("#apsReviewForm").submit(function(e) {
        var rvform = $(this),
            button = rvform.find(".aps-button"),
            rvdata = rvform.serialize();
        $.ajax({
            url: ajaxurl,
            type: "POST",
            data: rvdata,
            dataType: "json",
            beforeSend: function() {
                button.hide();
                button.after('<span class="aps-loading alignright"></span>');
            },
            success: function(res) {
                if (res.success) {
                    aps_response_msg("success", res.success, true);
                    rvform.trigger("reset");
                } else {
                    aps_response_msg("error", res.error, true);
                }
            },
            complete: function() {
                button.next(".aps-loading").remove();
                button.show();
            }
        });

        e.preventDefault();
    });

    // display ajax response message
    function aps_response_msg(icn, msg, auto) {
        if (icn == "success") {
            var content = '<span class="aps-msg-success"><span class="aps-icon-check"></span>' + msg + '</span>';
        } else if (icn == "error") {
            var content = '<span class="aps-msg-errors"><span class="aps-icon-attention"></span>' + msg + '</span>';
        } else {
            var content = msg;
        }

        $("body").append('<div class="aps-msg-overlay"></div><div class="aps-res-msg"><span class="aps-icon-cancel aps-close-box aps-close-icon"></span>' + content + '</div>');
        var msg_box = $(".aps-res-msg"),
            msg_overlay = $(".aps-msg-overlay"),
            box_height = msg_box.outerHeight() / 2,
            box_width = msg_box.outerWidth() / 2;
        msg_box.css({marginTop: "-" + box_height + "px", marginLeft: "-" + box_width + "px"});
        msg_overlay.fadeIn(200);
        msg_box.fadeIn(300);

        if (auto) {
            setTimeout(remove_box, 9999);
        }
        $(".aps-close-box").click(remove_box);

        function remove_box() {
            msg_box.fadeOut("slow", function() {
                $(this).remove();
                msg_overlay.fadeOut("fast", function() {
                    $(this).remove();
                });
            });
        }
    }
})(jQuery);