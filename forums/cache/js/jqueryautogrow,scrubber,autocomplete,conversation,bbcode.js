(function($) {
    $.fn.TextAreaExpander = function(minHeight, maxHeight) {
        var hCheck = !($.browser.msie || $.browser.opera);

        function ResizeTextarea(e) {
            e = e.target || e;
            var vlen = e.value.length,
                ewidth = e.offsetWidth;
            if (vlen != e.valLength || ewidth != e.boxWidth) {
                var scrollTop = $(document).scrollTop();
                if (hCheck && (vlen < e.valLength || ewidth != e.boxWidth)) e.style.height = e.expandMin + "px";
                var h = Math.max(e.expandMin, Math.min(e.scrollHeight, e.expandMax));
                e.style.overflow = (e.scrollHeight > h ? "auto" : "hidden");
                e.style.height = h + "px";
                $.scrollTo(scrollTop);
                e.valLength = vlen;
                e.boxWidth = ewidth;
            }
            return true;
        };
        this.each(function() {
            if (this.nodeName.toLowerCase() != "textarea") return;
            var p = this.className.match(/expand(\d+)\-*(\d+)*/i);
            this.expandMin = minHeight || (p ? parseInt('0' + p[1], 10) : 0);
            this.expandMax = maxHeight || (p ? parseInt('0' + p[2], 10) : 99999);
            $(this).css('box-sizing', 'border-box');
            ResizeTextarea(this);
            if (!this.Initialized) {
                this.Initialized = true;
                $(this).bind("keyup focus input", ResizeTextarea);
            }
        });
        return this;
    };
})(jQuery);
(function($) {
    $.fn.autoGrowInput = function(o) {
        o = $.extend({
            maxWidth: 1000,
            minWidth: 0,
            comfortZone: 70
        }, o);
        this.filter('input:text').each(function() {
            var minWidth = o.minWidth || $(this).width(),
                val = '',
                input = $(this),
                testSubject = $('<div/>').css({
                    position: 'absolute',
                    top: -9999,
                    left: -9999,
                    width: 'auto',
                    fontSize: input.css('fontSize'),
                    fontFamily: input.css('fontFamily'),
                    fontWeight: input.css('fontWeight'),
                    letterSpacing: input.css('letterSpacing'),
                    whiteSpace: 'nowrap'
                }),
                check = function() {
                    val = input.val();
                    var escaped = val.replace(/&/g, '&amp;').replace(/\s/g, ' ').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                    testSubject.html(escaped);
                    var testerWidth = testSubject.width(),
                        newWidth = (testerWidth + o.comfortZone) >= minWidth ? testerWidth + o.comfortZone : minWidth,
                        currentWidth = input.width(),
                        newWidth = Math.max(minWidth, Math.min(newWidth, o.maxWidth));
                    input.width(newWidth);
                };
            testSubject.insertAfter(input);
            $(this).bind('keyup keydown blur update', check);
        });
        return this;
    };
})(jQuery);
var ETScrubber = {
    header: null,
    body: null,
    scrubber: null,
    items: null,
    loadItemsCallback: null,
    scrollToIndexCallback: null,
    count: 0,
    startFrom: 0,
    perPage: 0,
    moreText: "Load more",
    loadedItems: [],
    init: function() {
        var count = Math.min(this.startFrom + this.perPage, this.count);
        for (var i = this.startFrom; i < count; i++)
            this.loadedItems.push(i);
        this.header = $("#hdr");
        var headerTop = this.header.offset().top;
        var headerWidth = this.header.width();
        var scrubberTop = this.scrubber.length && (this.scrubber.offset().top - this.header.outerHeight() - 20);
        $(window).scroll(function() {
            var y = $(this).scrollTop();
            $("> li", ETScrubber.items).each(function() {
                var item = $(this);
                if (y > item.offset().top + item.outerHeight() - ETScrubber.header.outerHeight()) return true;
                else if (item.data("index")) {
                    $(".scrubber li").removeClass("selected");
                    var index = item.data("index");
                    $(".scrubber-" + index, ETScrubber.scrubber).addClass("selected").parents("li").addClass("selected");
                    return false;
                }
            });
            var newer = $(".scrubberNext", ETScrubber.body);
            if (newer.length && y + $(window).height() > newer.offset().top && !newer.hasClass("loading") && !ET.disableFixedPositions) {
                newer.find("a").click();
            }
        }).scroll();
        $(ETScrubber.body).on("click", ".scrubberMore a", function(e) {
            e.preventDefault();
            $(this).parent().addClass("loading");
            var moreItem = $(this).parent();
            var backwards, position;
            if (moreItem.is(".scrubberPrevious")) {
                backwards = true;
                position = Math.min.apply(Math, ETScrubber.loadedItems) - ETScrubber.perPage;
            } else if (moreItem.is(".scrubberNext")) {
                backwards = false;
                position = Math.max.apply(Math, ETScrubber.loadedItems) + 1;
            } else {
                backwards = moreItem.offset().top - $(document).scrollTop() < 250;
                position = backwards ? $(this).parent().data("positionEnd") - ETScrubber.perPage + 1 : $(this).parent().data("positionStart");
            }
            ETScrubber.loadItemsCallback(position, function(data) {
                if (backwards) {
                    var firstItem = moreItem.next();
                    var scrollOffset = firstItem.offset().top - $(document).scrollTop();
                }
                var items = ETScrubber.addItems(data.startFrom, data.view, moreItem);
                if (backwards)
                    $.scrollTo(firstItem.offset().top - scrollOffset);
                return items;
            });
        });
        $(".scrubber a", ETScrubber.scrubber).click(function(e) {
            e.preventDefault();
            var index = $(this).parent().data("index");
            if (index == "last") index = Infinity;
            else if (index == "first") index = 0;
            var found = ETScrubber.scrollToIndex(index);
            if (!found) {
                var moreItem = null,
                    prevPost = null;
                $("li", ETScrubber.items).each(function() {
                    if ($(this).is(".scrubberMore")) moreItem = $(this);
                    else {
                        var item = $(this).first();
                        if (item.data("index") > index) return false;
                        moreItem = null;
                        prevPost = $(this);
                    }
                });
                if (!moreItem && !prevPost)
                    ETScrubber.scrollTo(0);
                else if (!moreItem && prevPost && index != Infinity)
                    ETScrubber.scrollTo(prevPost.offset().top);
                else if (moreItem) {
                    ETScrubber.scrollTo(moreItem.offset().top);
                    moreItem.addClass("loading");
                    ETScrubber.loadItemsCallback(index, function(data) {
                        if (index == Infinity) {
                            $('html,body').stop(true, true);
                            var scrollOffset = ETScrubber.items.offset().top + ETScrubber.items.outerHeight() - $(document).scrollTop();
                        }
                        var items = ETScrubber.addItems(data.startFrom, data.view, moreItem);
                        if (index == Infinity) $.scrollTo(ETScrubber.items.offset().top + ETScrubber.items.outerHeight() - scrollOffset);
                        else ETScrubber.scrollToIndex(index);
                        return items;
                    }, true);
                }
            }
        });
    },
    scrollTo: function(position) {
        $.scrollTo(Math.max(0, position - ETScrubber.header.outerHeight() - 20), "slow");
    },
    scrollToIndex: function(index) {
        var post = null,
            found = false,
            item;
        $("li", ETScrubber.items).each(function() {
            item = $(this);
            if (item.data("index") == index) {
                found = true;
                return false;
            }
            if (item.data("index") > index)
                return false;
        });
        if (item) ETScrubber.scrollTo(index == 0 ? 0 : $(item).offset().top);
        if (typeof ETScrubber.scrollToIndexCallback == "function") ETScrubber.scrollToIndexCallback(index);
        return found;
    },
    addItems: function(startFrom, items, moreItem, animate) {
        startFrom = parseInt(startFrom);
        moreItem.removeClass("loading");
        var view = $(items);
        view = view.filter("li");
        var items = [],
            newStartFrom = startFrom;
        for (var i = 0; i < ETScrubber.perPage; i++) {
            if (startFrom + i >= ETScrubber.count) break;
            if (ETScrubber.loadedItems.indexOf(startFrom + i) != -1) {
                if (items.length) break;
                newStartFrom = startFrom + i + 1;
                continue;
            }
            items.push(view[i]);
        }
        startFrom = newStartFrom;
        items = $(items);
        if ($("div.timeMarker[data-now]", ETScrubber.body).length) {
            items.find("div.timeMarker[data-now]").remove();
        }
        if (moreItem.is(".scrubberPrevious"))
            moreItem.after(items);
        else if (moreItem.is(".scrubberNext"))
            moreItem.before(items);
        else if (items.length)
            moreItem.replaceWith(items);
        var scrubberMore = $("<li class='scrubberMore'><a href='#'>" + ETScrubber.moreText + "</a></li>");
        if (ETScrubber.loadedItems.indexOf(startFrom - 1) == -1 && items.first().prev().is("li:not(.scrubberMore)")) {
            scrubberMore = scrubberMore.clone();
            items.first().before(scrubberMore);
            for (var i = startFrom - 1; i > 0; i--) {
                if (ETScrubber.loadedItems.indexOf(i) != -1) break;
            }
            scrubberMore.data("positionStart", i + 1);
            scrubberMore.data("positionEnd", startFrom - 1);
        }
        if (ETScrubber.loadedItems.indexOf(startFrom + items.length) == -1 && items.last().next().is("li:not(.scrubberMore)")) {
            scrubberMore = scrubberMore.clone();
            items.last().after(scrubberMore);
            for (var i = startFrom + items.length + 1; i < ETScrubber.count; i++) {
                if (ETScrubber.loadedItems.indexOf(i) != -1) break;
            }
            scrubberMore.data("positionStart", startFrom + items.length);
            scrubberMore.data("positionEnd", i - 1);
        }
        if (animate) items.hide().fadeIn("slow");
        for (var i = startFrom; i < startFrom + items.length; i++) {
            if (ETScrubber.loadedItems.indexOf(i) == -1)
                ETScrubber.loadedItems.push(i);
        }
        if (Math.min.apply(Math, ETScrubber.loadedItems) <= 0)
            $(".scrubberPrevious").remove();
        if (Math.max.apply(Math, ETScrubber.loadedItems) >= ETScrubber.count - 1)
            $(".scrubberNext").remove();
        return items;
    }
};

function ETAutoCompletePopup(field, character, clickHandler, insertField) {
    var ac = this;
    this.field = field;
    this.character = character;
    this.active = false;
    this.items = 0;
    this.index = 0;
    this.cache = [];
    this.searches = [];
    this.value = "";
    this.clickHandler = clickHandler;
    this.insertField = insertField || "name";
    if (!this.clickHandler) this.clickHandler = function(member) {
        var selection = ac.field.getSelection();
        var value = ac.field.val();
        var nameStart = 0;
        if (selection.length == 0) {
            for (var i = selection.start; i > selection.start - 20; i--) {
                if (i != selection.start && (value.substr(i, 1) == "]")) break;
                if (value.substr(i, ac.character.length) == ac.character) {
                    nameStart = i + ac.character.length;
                    break;
                }
            }
            if (nameStart) {
                ac.field.val(value.substring(0, nameStart) + member[ac.insertField] + " " + value.substr(selection.start));
                var p = nameStart + member[ac.insertField].length + 1;
                ac.field.selectRange(p, p);
            }
        }
    };
    this.popup = $("#autoCompletePopup-" + field.attr("id"));
    if (!this.popup.length) this.popup = $("<div id='autoCompletePopup-" + field.attr("id") + "'/>");
    this.popup.bind("mouseup", function(e) {
        return false;
    }).addClass("popup").addClass("autoCompletePopup").hide();
    this.popup.appendTo("body");
    $(document).mouseup(function(e) {
        ac.hide();
    });
    this.field.attr("autocomplete", "off").keydown(function(e) {
        if (ac.active) {
            switch (e.which) {
                case 40:
                    ac.updateIndex(ac.index + 1);
                    e.preventDefault();
                    break;
                case 38:
                    ac.updateIndex(ac.index - 1);
                    e.preventDefault();
                    break;
                case 13:
                case 9:
                    ac.popup.find("li").eq(ac.index).click();
                    e.preventDefault();
                    break;
                case 27:
                    ac.hide();
                    e.stopPropagation();
                    e.preventDefault();
                    break;
            }
        }
    });
    this.field.keyup(function(e) {
        switch (e.which) {
            case 27:
                if (ac.active) e.stopPropagation();
                break;
            case 9:
            case 13:
            case 27:
            case 40:
            case 38:
            case 37:
            case 39:
                break;
            default:
                if (ac.character) {
                    var selection = $(this).getSelection();
                    var value = $(this).val();
                    var nameStart = 0;
                    if (selection.length == 0) {
                        for (var i = selection.start; i > selection.start - 20; i--) {
                            if (i != selection.start && value.substr(i, 1) == "]") break;
                            if (value.substr(i, ac.character.length) == ac.character) {
                                nameStart = i + ac.character.length;
                                break;
                            }
                        }
                        if (nameStart) {
                            var name = value.substring(nameStart, selection.start);
                            ac.fetchNewContent(name);
                        }
                    }
                } else ac.fetchNewContent($(this).val());
                break;
        }
    });
    this.update = function() {
        if (ac.value) {
            var value = ac.value.replace(/ /g, "\xA0");
            value = value.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&");
            var regexp = new RegExp("(" + value + ")", "i");
            var results = [];
            for (var i in ac.cache) {
                if (regexp.test(ac.cache[i].name)) {
                    results.push(ac.cache[i]);
                }
            }
            ac.popup.html("<ul class='popupMenu'></ul>");
            if (results.length) {
                results = results.sort(function(a, b) {
                    return a.name == b.name ? 0 : (a.name < b.name ? -1 : 1);
                });
                results = results.slice(0, 5);
                var item;
                for (var i in results) {
                    var name = $("<div/>").text(results[i].name).html();
                    name = name.replace(regexp, "<strong>$1</strong>");
                    item = $("<li><a href='#'><i>" + results[i].avatar + "</i> " + name + "</a></li>").data("position", i).data("member", results[i]).mouseover(function() {
                        ac.updateIndex($(this).data("position"));
                    }).click(function(e) {
                        e.preventDefault();
                        ac.clickHandler($(this).data("member"));
                        ac.stop();
                    });
                    ac.popup.find("ul").append(item);
                }
                ac.items = results.length;
                ac.active = true;
                ac.show();
                ac.updateIndex(ac.index);
            } else ac.hide();
        } else ac.hide();
    }
    this.timeout = null;
    this.fetchNewContent = function(value) {
        if (value && value != ac.value && ac.searches.indexOf(value) == -1 && value.length >= 2) {
            clearTimeout(ac.timeout);
            ac.timeout = setTimeout(function() {
                $.ETAjax({
                    id: "autoComplete",
                    url: "members/autocomplete.ajax/" + encodeURIComponent(value),
                    global: false,
                    success: function(data) {
                        results: for (var i in data.results) {
                            for (var j in ac.cache) {
                                if (ac.cache[j].type == data.results[i].type && ac.cache[j].memberId == data.results[i].memberId) continue results;
                            }
                            ac.cache.push(data.results[i]);
                        }
                        ac.searches.push(value);
                        ac.update();
                    }
                });
            }, 250);
        }
        ac.value = value;
        ac.update();
    }
    this.show = function() {
        ac.popup.show().css({
            position: "absolute",
            zIndex: 9999
        });
        if (ac.character) {
            var selection = ac.field.getSelection();
            var value = ac.field.val().substr(0, selection.start - ac.value.length);
            var testSubject = $('<div/>').css({
                position: 'absolute',
                top: ac.field.offset().top,
                left: ac.field.offset().left,
                width: ac.field.width(),
                height: ac.field.height(),
                fontSize: ac.field.css('fontSize'),
                fontFamily: ac.field.css('fontFamily'),
                fontWeight: ac.field.css('fontWeight'),
                paddingTop: ac.field.css('paddingTop'),
                paddingLeft: ac.field.css('paddingLeft'),
                paddingRight: ac.field.css('paddingRight'),
                paddingBottom: ac.field.css('paddingBottom'),
                letterSpacing: ac.field.css('letterSpacing'),
                lineHeight: ac.field.css('lineHeight')
            }).html(value.replace(/[\n\r]/g, "<br/>")).appendTo("body").append("<span style='position:absolute'>&nbsp;</span>");
            var offset = testSubject.find("span").offset();
            ac.popup.css({
                left: offset.left,
                top: offset.top + testSubject.find("span").height()
            });
            testSubject.remove();
        } else ac.popup.css({
            left: ac.field.offset().left,
            top: ac.field.offset().top + ac.field.outerHeight() - 1,
            width: ac.field.outerWidth()
        });
        ac.active = true;
    }
    this.hide = function() {
        ac.popup.hide();
        ac.active = false;
    }
    this.stop = function() {
        ac.hide();
        clearTimeout(ac.timeout);
        $.ETAjax.abort("autoComplete");
    }
    this.updateIndex = function(index) {
        ac.index = index;
        if (ac.index < 0) ac.index = ac.items - 1;
        else if (ac.index >= ac.items) ac.index = 0;
        ac.popup.find("li").removeClass("selected").eq(ac.index).addClass("selected");
    }
};
var ETConversation = {
    id: 0,
    title: "",
    channel: "",
    slug: "",
    startFrom: 0,
    searchString: null,
    postCount: 0,
    updateInterval: null,
    editingReply: false,
    editingPosts: 0,
    init: function() {
        if (ET.conversation) {
            this.id = ET.conversation.conversationId;
            this.postCount = ET.conversation.countPosts;
            this.startFrom = ET.conversation.startFrom;
            this.channel = ET.conversation.channel;
            this.slug = ET.conversation.slug;
            this.searchString = ET.conversation.searchString;
            if ($("#conversationControls").length)
                $("#conversation .search").after($("#conversationControls").popup({
                    alignment: "right",
                    content: "<i class='icon-cog'></i> <span class='text'>" + T("Controls") + "</span> <i class='icon-caret-down'></i>"
                }).find(".button").addClass("big").end());
            ETScrubber.body = $("#conversation");
            ETScrubber.scrubber = $("#conversation .scrubberContent");
            ETScrubber.items = $("#conversationPosts");
            ETScrubber.count = this.postCount;
            ETScrubber.perPage = ET.postsPerPage;
            ETScrubber.moreText = T("Load more posts");
            ETScrubber.startFrom = this.startFrom;
            ETScrubber.loadItemsCallback = function(position, success, index) {
                if (position == Infinity) position = "999999";
                if (index) {
                    position = ("" + position).substr(0, 4) + "/" + ("" + position).substr(4, 2);
                }
                $.ETAjax({
                    url: "conversation/index.ajax/" + ETConversation.id + "/" + position,
                    data: {
                        search: ETConversation.searchString
                    },
                    success: function(data) {
                        var items = success(data);
                        ETConversation.initPost(items);
                        ETConversation.redisplayAvatars();
                    },
                    global: false
                });
            }
            ETScrubber.scrollToIndexCallback = function(index) {
                var position;
                if (index == Infinity) position = "last";
                else position = ("" + index).substr(0, 4) + "/" + ("" + index).substr(4, 2);
                $.history.load(ETConversation.slug + "/" + position, true);
            }
            ETScrubber.init();
            $("#jumpToReply").click(function(e) {
                e.preventDefault();
                $(".scrubber-now a").click();
                setTimeout(function() {
                    $("#reply textarea").click();
                }, 1);
            });
            this.updateInterval = new ETIntervalCallback(this.update, ET.conversation.updateInterval);
            this.title = $("#conversationTitle a").text() || $("#conversationTitle").text();
            $(document).on("click", "#conversationTitle a", function(e) {
                e.preventDefault();
                ETConversation.editTitle();
            });
            $("#control-sticky").click(function(e) {
                e.preventDefault();
                ETConversation.toggleSticky();
            });
            $("#control-lock").click(function(e) {
                e.preventDefault();
                ETConversation.toggleLock();
            });
            $("#control-ignore").click(function(e) {
                e.preventDefault();
                ETConversation.toggleIgnore();
            });
            $("#control-delete").click(function(e) {
                ETIntervalCallback.pause();
                if (!ETConversation.confirmDelete()) {
                    e.preventDefault();
                    ETIntervalCallback.resume();
                }
            });
            $("#conversationHeader .label").tooltip();
            this.initPosts();
            var hash = window.location.hash.replace("#", "");
            if (hash.substr(0, 1) == "p" && $("#" + hash).length) {
                ETConversation.highlightPost($("#" + hash));
                setTimeout(function() {
                    ETConversation.scrollTo($("#" + hash).offset().top - 10);
                }, 100);
            }
            $(window).scroll(function() {
                var y = $(this).scrollTop();
                var title = $("#forumTitle");
                if (y > $("#hdr").height()) {
                    if (!title.data("old")) {
                        title.data("old", title.html()).html("<a href='#'></a>").find("a").text(ETConversation.title);
                        title.find("a").click(function(e) {
                            e.preventDefault();
                            $.scrollTo(0, "fast");
                        });
                    }
                } else if (title.data("old")) {
                    title.html(title.data("old")).data("old", null);
                }
            });
        } else {
            $("#conversationTitle input").autoGrowInput({
                comfortZone: 30,
                minWidth: 300,
                maxWidth: 500
            }).trigger("update");
            $("#membersAllowedSheet").parents("form").remove();
            ETConversation.changeChannel();
        }
        $("#control-changeChannel").click(function(e) {
            e.preventDefault();
            ETConversation.changeChannel();
        });
        $("#control-changeMembersAllowed").click(function(e) {
            e.preventDefault();
            ETConversation.changeMembersAllowed();
        });
        $(".channels a").tooltip({
            alignment: "left",
            delay: 250,
            className: "withArrow withArrowBottom"
        });
        ETMembersAllowedTooltip.init($("#conversationPrivacy .allowedList .showMore"), function() {
            return ETConversation.id;
        }, true);
        if ($("#reply").length) ETConversation.initReply();
        $(window).bind("beforeunload.conversation", ETConversation.beforeUnload);
    },
    scrollTo: function(position) {
        ETScrubber.scrollTo(position);
    },
    beforeUnload: function onbeforeunload() {
        if (ETConversation.editingPosts > 0) return T("message.confirmLeave");
        else if (ETConversation.editingReply) return T("message.confirmDiscardPost");
    },
    replyShowing: false,
    initReply: function() {
        var textarea = $("#reply textarea");
        ETConversation.editingReply = false;
        if (!ET.iOS) textarea.TextAreaExpander(200, 700);
        if (!textarea.val()) $("#reply .postReply").disable();
        $("#reply .saveDraft").disable();
        textarea.keyup(function(e) {
            if (e.ctrlKey) return;
            $("#reply .postReply, #reply .saveDraft")[$(this).val() ? "enable" : "disable"]();
            ETConversation.editingReply = $(this).val() ? true : false;
        });
        if (ET.mentions) new ETAutoCompletePopup($("#reply textarea"), "@");
        $("#reply .saveDraft").click(function(e) {
            ETConversation.saveDraft();
            e.preventDefault();
        });
        $("#reply .discardDraft").click(function(e) {
            ETConversation.discardDraft();
            e.preventDefault();
        });
        $("#reply .postReply").click(function(e) {
            if (ETConversation.id) ETConversation.addReply();
            else ETConversation.startConversation();
            e.preventDefault();
        });
        if (!$("#reply").hasClass("logInToReply") && ETConversation.id) {
            $("#reply").addClass("replyPlaceholder");
            $("#reply").click(function(e) {
                if (!ETConversation.replyShowing) {
                    $(this).trigger("change");
                    var scrollTop = $(document).scrollTop();
                    $("#reply textarea").focus();
                    $.scrollTo(scrollTop);
                    $.scrollTo("#reply", "slow");
                }
                e.stopPropagation();
            });
            $("#reply").change(function(e) {
                if (!ETConversation.replyShowing) {
                    ETConversation.replyShowing = true;
                    $("#reply").removeClass("replyPlaceholder");
                    var pos = textarea.val().length;
                    textarea.selectRange(pos, pos);
                }
            });
            if ($("#reply textarea").val()) $("#reply").trigger("change");
            $(document).click(function(e) {
                ETConversation.hideReply();
            });
        }
        $("#reply .controls a").tooltip();
        $("#reply .discardDraft").tooltip();
        textarea.keydown(function(e) {
            if (e.ctrlKey && e.which == 13 && !$("#reply .postReply").prop("disabled")) {
                $("#reply .postReply").click();
                e.preventDefault();
            }
        });
    },
    hideReply: function() {
        if (!ETConversation.replyShowing || $("#reply textarea").val()) return;
        var scrollTop = $(document).scrollTop();
        var oldHeight = $("#reply .postContent").height();
        ETConversation.replyShowing = false;
        $("#reply").addClass("replyPlaceholder");
        var newHeight = $("#reply .postContent").height();
        $("#reply .postContent").height(oldHeight).animate({
            height: newHeight
        }, "fast", function() {
            $(this).height("");
        });
        $.scrollTo(scrollTop);
        ETConversation.editingReply = false;
    },
    resetReply: function() {
        $("#reply textarea").val("");
        ETConversation.togglePreview("reply", false);
        ETConversation.hideReply();
    },
    addReply: function() {
        var content = $("#reply textarea").val();
        $("#reply .postReply, #reply .saveDraft").disable();
        $.ETAjax({
            url: "conversation/reply.ajax/" + ETConversation.id,
            type: "post",
            data: {
                conversationId: ETConversation.id,
                content: content
            },
            success: function(data) {
                if (!data.postId) {
                    $("#reply .postReply, #reply .saveDraft").enable();
                    return;
                }
                ETMessages.hideMessage("waitToReply");
                ETMessages.hideMessage("emptyPost");
                $("#conversationHeader .labels .label-draft").remove();
                ETConversation.resetReply();
                ETConversation.postCount++;
                var moreItem = $("<li></li>").appendTo("#conversationPosts");
                ETScrubber.count = ETConversation.postCount;
                var items = ETScrubber.addItems(ETConversation.postCount - 1, data.view, moreItem, true);
                ETConversation.redisplayAvatars();
                ETConversation.initPost(items);
                if (data.starOnReply) {
                    toggleStarState(ETConversation.id, true);
                }
                ETConversation.updateInterval.reset(ET.conversationUpdateIntervalStart);
            },
            beforeSend: function() {
                createLoadingOverlay("reply", "reply");
            },
            complete: function() {
                hideLoadingOverlay("reply", false);
            }
        });
    },
    startConversation: function(draft) {
        var title = $("#conversationTitle input").val();
        var content = $("#reply textarea").val();
        var channel = $("#conversationHeader .channels :radio:checked").val();
        $("#reply .postReply, #reply .saveDraft").disable();
        var data = {
            title: title,
            content: content,
            channel: channel
        };
        if (draft) data.saveDraft = "1";
        $.ETAjax({
            url: "conversation/start.ajax",
            type: "post",
            data: data,
            beforeSend: function() {
                createLoadingOverlay("reply", "reply");
                ETConversation.editingReply = false;
            },
            complete: function() {
                hideLoadingOverlay("reply", false);
                $("#reply .postReply, #reply .saveDraft").enable();
            },
            success: function(data) {
                if (data.messages) {
                    if (data.messages.title) $("#conversationTitle input").focus();
                    if (data.messages.content) $("#reply textarea").focus();
                    return;
                }
            }
        });
    },
    saveDraft: function() {
        if (!ETConversation.id) {
            ETConversation.startConversation(true);
            return;
        }
        $.ETAjax({
            url: "conversation/reply.ajax/" + ETConversation.id,
            type: "post",
            data: {
                saveDraft: true,
                content: $("#reply textarea").val()
            },
            beforeSend: function() {
                createLoadingOverlay("reply", "reply");
            },
            complete: function() {
                hideLoadingOverlay("reply", false);
            },
            success: function(data) {
                if (data.messages) return;
                ETMessages.hideMessage("emptyPost");
                $("#conversationHeader .labels").html(data.labels);
                $("#reply .saveDraft").disable();
                ETConversation.editingReply = false;
            }
        });
    },
    discardDraft: function() {
        if (!confirm(T("message.confirmDelete"))) return;
        $(window).unbind("beforeunload.conversation");
        $.ETAjax({
            url: "conversation/discard.ajax/" + ETConversation.id,
            type: "post",
            beforeSend: function() {
                createLoadingOverlay("reply", "reply");
            },
            complete: function() {
                hideLoadingOverlay("reply", false);
            },
            success: function(data) {
                $("#conversationHeader .labels").html(data.labels);
                ETConversation.resetReply();
                $(window).bind("beforeunload.conversation", ETConversation.beforeUnload);
            }
        });
    },
    update: function() {
        if (ETConversation.searchString || ETScrubber.loadedItems.indexOf(ETConversation.postCount - 1) == -1) return;
        $.ETAjax({
            url: "conversation/index.ajax/" + ETConversation.id + "/" + ETConversation.postCount,
            success: function(data) {
                if (ETConversation.postCount < data.countPosts) {
                    ETConversation.postCount = data.countPosts;
                    var moreItem = $("<li></li>").appendTo("#conversationPosts");
                    ETScrubber.count = ETConversation.postCount;
                    ETScrubber.addItems(data.startFrom, data.view, moreItem, true);
                    var interval = ET.conversationUpdateIntervalStart;
                } else var interval = Math.min(ET.conversationUpdateIntervalLimit, ETConversation.updateInterval.interval * ET.conversationUpdateIntervalMultiplier);
                ETConversation.updateInterval.reset(interval);
            },
            global: false
        });
    },
    initPosts: function() {
        $("#conversationPosts .controls a, #conversationPosts .controls span").tooltip({
            alignment: "center"
        });
        $("#conversationPosts h3 a").tooltip({
            alignment: "left",
            className: "withArrow withArrowBottom"
        });
        $("#conversationPosts .time").tooltip({
            alignment: "left",
            className: "withArrow withArrowBottom"
        });
        $("#conversationPosts .online").tooltip({
            alignment: "left",
            offset: [-9, 0],
            className: "withArrow withArrowBottom"
        }).css("cursor", "pointer");
        $(document).on("click", "#conversationPosts .controls .control-edit:not(.disabled)", function(e) {
            var postId = $(this).parents(".post").data("id");
            ETConversation.editPost(postId);
            e.preventDefault();
        });
        $(document).on("click", "#conversationPosts .controls .control-delete:not(.disabled)", function(e) {
            var postId = $(this).parents(".post").data("id");
            ETConversation.deletePost(postId);
            e.preventDefault();
        });
        $(document).on("click", "#conversationPosts .controls .control-restore", function(e) {
            var postId = $(this).parents(".post").data("id");
            ETConversation.restorePost(postId);
            e.preventDefault();
        });
        $(document).on("click", "#conversationPosts .post:not(.edit) .controls .control-quote", function(e) {
            var postId = $(this).parents(".post").data("id");
            ETConversation.quotePost(postId, e.shiftKey);
            e.preventDefault();
        });
        $(document).on("click", "#conversationPosts .postBody a[rel=post]", function(e) {
            var id = $(this).data("id");
            $("#conversationPosts .post").each(function() {
                if ($(this).data("id") == id) {
                    ETConversation.scrollTo($(this).offset().top - 10);
                    ETConversation.highlightPost($("#p" + id));
                    e.preventDefault();
                    return false;
                }
            });
        });
        ETConversation.initPost($("#conversationPosts .post"));
    },
    initPost: function(post) {
        ETConversation.collapseQuotes(post);
        $(post).find('[data-timestamp]').each(function() {
            $this = $(this);
            var date = new Date(parseInt($this.data('timestamp')) * 1000);
            $this.attr('title', date.toLocaleString());
        })
    },
    collapseQuotes: function(items) {
        $(".postBody blockquote:not(.collapsed)", items).addClass("collapsed").each(function() {
            if ($(this)[0].scrollHeight <= $(this).innerHeight() + 20) {
                $(this).removeClass("collapsed");
                return;
            }
            var link = $("<a href='#' class='expand'><i class='icon-ellipsis-horizontal'></i></a>");
            link.click(function(e) {
                e.preventDefault();
                $(this).parents("blockquote").removeClass("collapsed");
                $(this).remove();
            });
            $(this).append(link);
        });
    },
    highlightPost: function(post) {
        $("#conversationPosts .post.highlight").removeClass("highlight");
        $(post).addClass("highlight");
    },
    redisplayAvatars: function() {
        var prevId = null;
        $("#conversationPosts > li").each(function() {
            if (prevId == $(this).find("div.post").data("memberid"))
                $(this).find("div.avatar").hide();
            else
                $(this).find("div.avatar").show();
            prevId = $(this).find("div.post").data("memberid");
        });
    },
    deletePost: function(postId) {
        $.hideToolTip();
        $.ETAjax({
            url: "conversation/deletePost.ajax/" + postId,
            beforeSend: function() {
                createLoadingOverlay("p" + postId, "p" + postId);
            },
            complete: function() {
                hideLoadingOverlay("p" + postId, true);
            },
            success: function(data) {
                if (data.messages || data.modalMessage) return;
                $("#p" + postId).replaceWith(data.view);
                ETConversation.redisplayAvatars();
            }
        });
    },
    restorePost: function(postId) {
        $.hideToolTip();
        $.ETAjax({
            url: "conversation/restorePost.ajax/" + postId,
            beforeSend: function() {
                createLoadingOverlay("p" + postId, "p" + postId);
            },
            complete: function() {
                hideLoadingOverlay("p" + postId, true);
            },
            success: function(data) {
                if (data.messages || data.modalMessage) return;
                $("#p" + postId).replaceWith(data.view);
                ETConversation.redisplayAvatars();
                ETConversation.collapseQuotes($("#p" + postId));
            }
        });
    },
    editPost: function(postId) {
        $.hideToolTip();
        var post = $("#p" + postId);
        $.ETAjax({
            url: "conversation/editPost.ajax/" + postId,
            type: "get",
            beforeSend: function() {
                createLoadingOverlay("p" + postId, "p" + postId);
            },
            complete: function() {
                hideLoadingOverlay("p" + postId, true);
            },
            success: function(data) {
                if (data.messages || data.modalMessage) return;
                ETConversation.updateEditPost(postId, data.view);
            }
        });
    },
    updateEditPost: function(postId, html) {
        var post = $("#p" + postId);
        ETConversation.editingPosts++;
        var startHeight = $(".postContent", post).height();
        post.replaceWith($(html).find(".post"));
        var newPost = $("#p" + postId);
        var textarea = $("textarea", newPost);
        newPost.data("oldPost", post);
        var len = textarea.val().length;
        if (!ET.iOS) textarea.TextAreaExpander(200, 700).focus().selectRange(len, len);
        new ETAutoCompletePopup(textarea, "@");
        $(".cancel", newPost).click(function(e) {
            e.preventDefault();
            ETConversation.cancelEditPost(postId);
        });
        $(".submit", newPost).click(function(e) {
            e.preventDefault();
            ETConversation.saveEditPost(postId, textarea.val());
        });
        var newHeight = $(".postContent", newPost).height();
        $(".postContent", newPost).height(startHeight).animate({
            height: newHeight
        }, "fast", function() {
            $(this).height("");
        });
        ETConversation.redisplayAvatars();
        var scrollTo = newPost.offset().top + newHeight - $(window).height() + 10;
        if ($(document).scrollTop() < scrollTo) $.scrollTo(scrollTo, "slow");
        textarea.keydown(function(e) {
            if (e.ctrlKey && e.which == 13) {
                ETConversation.saveEditPost(postId, this.value);
                e.preventDefault();
            }
            if (e.which == 27) {
                ETConversation.cancelEditPost(postId);
                e.preventDefault();
            }
        });
    },
    saveEditPost: function(postId, content) {
        var post = $("#p" + postId);
        $(".button", post).disable();
        $.ETAjax({
            url: "conversation/editPost.ajax/" + postId,
            type: "post",
            data: {
                content: content,
                save: true
            },
            beforeSend: function() {
                createLoadingOverlay("p" + postId, "p" + postId);
            },
            complete: function() {
                hideLoadingOverlay("p" + postId, true);
                $(".button", post).enable();
            },
            success: function(data) {
                if (data.messages) return;
                var startHeight = $(".postContent", post).height();
                post.replaceWith(data.view);
                var newPost = $("#p" + postId);
                var newHeight = $(".postContent", newPost).height();
                $(".postContent", newPost).height(startHeight).animate({
                    height: newHeight
                }, "fast", function() {
                    $(this).height("");
                });
                ETConversation.editingPosts--;
                ETConversation.redisplayAvatars();
                ETConversation.initPost(newPost);
            }
        });
    },
    cancelEditPost: function(postId) {
        ETConversation.editingPosts--;
        var post = $("#p" + postId);
        var scrollTop = $(document).scrollTop();
        var startHeight = $(".postContent", post).height();
        post.replaceWith(post.data("oldPost"));
        var newPost = $("#p" + postId);
        var newHeight = $(".postContent", newPost).height();
        $(".postContent", newPost).height(startHeight).animate({
            height: newHeight
        }, "fast", function() {
            $(this).height("");
        });
        ETConversation.initPost(newPost);
        $.scrollTo(scrollTop);
    },
    quotePost: function(postId, multi) {
        var selection = "" + $.getSelection();
        $.ETAjax({
            url: "conversation/quotePost.json/" + postId,
            success: function(data) {
                var top = $(document).scrollTop();
                ETConversation.quote("reply", selection ? selection : data.content, data.postId + ":" + data.member, null, true);
                if (!multi) {
                    $("#jumpToReply").click();
                } else {
                    $("#reply").change();
                    $.scrollTo(top);
                }
            },
            global: true
        });
    },
    initMembersAllowed: function() {
        $("#addMemberForm .help").toggle($("#membersAllowedSheet .allowedList .name").length ? true : false);
        var ac = new ETAutoCompletePopup($("#addMemberForm input[name=member]"), false, function(member) {
            ETConversation.addMember(member.name);
        });
        $("#addMemberForm").wrap("<form>");
        $("#addMemberForm").parent().submit(function(e) {
            ETConversation.addMember($("#addMemberForm input[name=member]").val());
            ac.stop();
            e.preventDefault();
        });
        var selector = "#membersAllowedSheet .allowedList .name a";
        $(document).off("click", selector).on("click", selector, function(e) {
            e.preventDefault();
            ETConversation.removeMember($(this).data("type"), $(this).data("id"));
        });
        $("#addMemberForm input[name=member]").focus();
    },
    changeMembersAllowed: function() {
        ETSheet.loadSheet("membersAllowedSheet", "conversation/membersAllowed.ajax/" + ETConversation.id, function() {
            ETConversation.initMembersAllowed();
        });
    },
    addMember: function(name) {
        if (!name) return;
        $.ETAjax({
            id: "addMember",
            url: "conversation/addMember.ajax/" + ETConversation.id,
            type: "post",
            data: {
                member: name
            },
            success: function(data) {
                if (data.messages) $("#addMemberForm input[name=member]").select();
                else {
                    $("#addMemberForm .help").show();
                    $("#addMemberForm input[name=member]").val("");
                    $("#conversationPrivacy .allowedList").html(data.allowedSummary);
                    $("#membersAllowedSheet .allowedList").html(data.allowedList);
                    $("#conversationHeader .labels").html(data.labels);
                    ETMembersAllowedTooltip.init($("#conversationPrivacy .allowedList .showMore"), function() {
                        return ETConversation.id;
                    }, true);
                }
            }
        });
    },
    removeMember: function(type, id) {
        var data = {};
        data[type] = id;
        $.ETAjax({
            id: "addMember",
            url: "conversation/removeMember.ajax/" + ETConversation.id,
            type: "post",
            data: data,
            success: function(data) {
                $("#conversationPrivacy .allowedList").html(data.allowedSummary);
                $("#membersAllowedSheet .allowedList").html(data.allowedList);
                $("#addMemberForm .help").toggle($("#membersAllowedSheet .allowedList .name").length ? true : false);
                $("#conversationHeader .labels").html(data.labels);
                ETMembersAllowedTooltip.init($("#conversationPrivacy .allowedList .showMore"), function() {
                    return ETConversation.id;
                }, true);
            }
        });
    },
    changeChannel: function() {
        ETSheet.loadSheet("changeChannelSheet", "conversation/changeChannel.view/" + ETConversation.id, function() {
            $("#changeChannelSheet .channelList input").hide().click(function() {
                $("#changeChannelSheet form").submit();
            });
            $("#changeChannelSheet .buttons").hide();
            $("#changeChannelSheet .channelList li").tooltip({
                alignment: "left",
                offset: [20, 43],
                className: "hoverable"
            });
            $("#changeChannelSheet form").submit(function(e) {
                e.preventDefault();
                var channelId = $(this).find("input:checked").val();
                $.ETAjax({
                    url: "conversation/save.json/" + ETConversation.id,
                    type: "post",
                    data: {
                        channel: channelId
                    },
                    success: function(data) {
                        if (data.messages) return;
                        $("#conversationPrivacy .allowedList").html(data.allowedSummary);
                        ETMembersAllowedTooltip.init($("#conversationPrivacy .allowedList .showMore"), function() {
                            return ETConversation.id;
                        }, true);
                        ETConversation.channel = channelId;
                        $("#conversationHeader .channels").replaceWith(data.channelPath);
                        ETSheet.hideSheet("changeChannelSheet");
                    }
                })
            });
        });
    },
    editTitle: function() {
        if (!$("#conversationTitle").hasClass("editing")) {
            var title = $("#conversationTitle a").text().trim();
            $("#conversationTitle").html("<input type='text' class='text' maxlength='100'/>").addClass("editing");
            $("#conversationTitle input").val(title).autoGrowInput({
                comfortZone: 30,
                minWidth: 250,
                maxWidth: 500
            }).trigger("update");
            $("#conversationTitle input").select().blur(function() {
                ETConversation.saveTitle();
            }).keydown(function(e) {
                if (e.which == 13) ETConversation.saveTitle();
                if (e.which == 27) ETConversation.saveTitle(true);
            });
        }
    },
    saveTitle: function(cancel) {
        if ($("#conversationTitle").hasClass("editing")) {
            var title = $("#conversationTitle input").val();
            if (!title || cancel) title = ETConversation.title;
            var sanitized = $('<div/>').text(title).html();
            $("#conversationTitle").html("<a href='#'>" + sanitized + "</a>").removeClass("editing");
            if (cancel || ETConversation.title == title) return;
            $(document).attr("title", $(document).attr("title").replace(ETConversation.title, title));
            ETConversation.title = title;
            $.ETAjax({
                url: "conversation/save.json/" + ETConversation.id,
                type: "post",
                data: {
                    title: title
                },
                global: true
            });
        }
    },
    toggleSticky: function() {
        $("#control-sticky span").html(T($("#control-sticky span").html() == T("Sticky") ? "Unsticky" : "Sticky"));
        $.ETAjax({
            url: "conversation/sticky.ajax/" + ETConversation.id,
            success: function(data) {
                $("#conversationHeader .labels").html(data.labels);
            }
        });
    },
    toggleLock: function() {
        $("#control-lock span").html(T($("#control-lock span").html() == T("Lock") ? "Unlock" : "Lock"));
        $.ETAjax({
            url: "conversation/lock.ajax/" + ETConversation.id,
            success: function(data) {
                $("#conversationHeader .labels").html(data.labels);
            }
        });
    },
    toggleIgnore: function() {
        $("#control-ignore span").html(T($("#control-ignore span").html() == T("Ignore conversation") ? "Unignore conversation" : "Ignore conversation"));
        $.ETAjax({
            url: "conversation/ignore.ajax/" + ETConversation.id,
            success: function(data) {
                $("#conversationHeader .labels").html(data.labels);
            }
        });
    },
    confirmDelete: function() {
        return confirm(T("message.confirmDelete"));
    },
    quote: function(id, quote, name, postId, insert) {
        var argument = postId || name ? (postId ? postId + ":" : "") + (name ? name : "Name") : "";
        var startTag = "[quote" + (argument ? "=" + argument : "") + "]" + (quote ? quote : "");
        var endTag = "[/quote]";
        if (insert) ETConversation.insertText($("#" + id + " textarea"), startTag + endTag + "\n");
        else ETConversation.wrapText($("#" + id + " textarea"), startTag, endTag);
    },
    insertText: function(textarea, text) {
        textarea = $(textarea);
        textarea.focus();
        textarea.val(textarea.val() + text);
        textarea.focus();
        textarea.trigger("keyup");
    },
    wrapText: function(textarea, tagStart, tagEnd, selectArgument, defaultArgumentValue) {
        textarea = $(textarea);
        var scrollTop = textarea.scrollTop();
        var selectionInfo = textarea.getSelection();
        if (textarea.val().substring(selectionInfo.start, selectionInfo.start + 1).match(/ /)) selectionInfo.start++;
        if (textarea.val().substring(selectionInfo.end - 1, selectionInfo.end).match(/ /)) selectionInfo.end--;
        var selection = textarea.val().substring(selectionInfo.start, selectionInfo.end);
        selection = selection ? selection : (defaultArgumentValue ? defaultArgumentValue : "");
        var text = tagStart + selection + (typeof tagEnd != "undefined" ? tagEnd : tagStart);
        textarea.val(textarea.val().substr(0, selectionInfo.start) + text + textarea.val().substr(selectionInfo.end));
        textarea.scrollTo(scrollTop);
        textarea.focus();
        if (selectArgument) {
            var newStart = selectionInfo.start + tagStart.indexOf(selectArgument);
            var newEnd = newStart + selectArgument.length;
        } else {
            var newStart = selectionInfo.start + tagStart.length;
            var newEnd = newStart + selection.length;
        }
        textarea.selectRange(newStart, newEnd);
        textarea.trigger("keyup");
    },
    togglePreview: function(id, preview) {
        if (preview) {
            $("#" + id + " .formattingButtons").hide();
            $("#" + id + "-preview").html("");
            $.ETAjax({
                url: "conversation/preview.ajax",
                type: "post",
                data: {
                    content: $("#" + id + " textarea").val()
                },
                success: function(data) {
                    $("#" + id + "-preview").css("min-height", $("#" + id + "-textarea").innerHeight());
                    $("#" + id + " textarea").hide();
                    $("#" + id + "-preview").show()
                    $("#" + id + "-preview").html(data.content);
                }
            });
        } else {
            $("#" + id + " .formattingButtons").show();
            $("#" + id + " textarea").show();
            $("#" + id + "-preview").hide();
            $("#reply-previewCheckbox").prop("checked", false);
        }
    }
};
$(function() {
    ETConversation.init();
});
var BBCode = {
    bold: function(id) {
        ETConversation.wrapText($("#" + id + " textarea"), "[b]", "[/b]");
    },
    italic: function(id) {
        ETConversation.wrapText($("#" + id + " textarea"), "[i]", "[/i]");
    },
    strikethrough: function(id) {
        ETConversation.wrapText($("#" + id + " textarea"), "[s]", "[/s]");
    },
    header: function(id) {
        ETConversation.wrapText($("#" + id + " textarea"), "[h]", "[/h]");
    },
    link: function(id) {
        ETConversation.wrapText($("#" + id + " textarea"), "[url=http://example.com]", "[/url]", "http://example.com", "link text");
    },
    image: function(id) {
        ETConversation.wrapText($("#" + id + " textarea"), "[img]", "[/img]", "", "http://example.com/image.jpg");
    },
    fixed: function(id) {
        ETConversation.wrapText($("#" + id + " textarea"), "[code]", "[/code]");
    },
};
