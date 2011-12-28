
google.load("jquery",1);google.load("jqueryui",1);!function($){"use strict"
var transitionEnd
$(document).ready(function(){$.support.transition=(function(){var thisBody=document.body||document.documentElement,thisStyle=thisBody.style,support=thisStyle.transition!==undefined||thisStyle.WebkitTransition!==undefined||thisStyle.MozTransition!==undefined||thisStyle.MsTransition!==undefined||thisStyle.OTransition!==undefined
return support})()
if($.support.transition){transitionEnd="TransitionEnd"
if($.browser.webkit){transitionEnd="webkitTransitionEnd"}else if($.browser.mozilla){transitionEnd="transitionend"}else if($.browser.opera){transitionEnd="oTransitionEnd"}}})
var Twipsy=function(element,options){this.$element=$(element)
this.options=options
this.enabled=true
this.fixTitle()}
Twipsy.prototype={show:function(){var pos,actualWidth,actualHeight,placement,$tip,tp
if(this.hasContent()&&this.enabled){$tip=this.tip()
this.setContent()
if(this.options.animate){$tip.addClass('fade')}
$tip.remove().css({top:0,left:0,display:'block'}).prependTo(document.body)
pos=$.extend({},this.$element.offset(),{width:this.$element[0].offsetWidth,height:this.$element[0].offsetHeight})
actualWidth=$tip[0].offsetWidth
actualHeight=$tip[0].offsetHeight
placement=maybeCall(this.options.placement,this,[$tip[0],this.$element[0]])
switch(placement){case'below':tp={top:pos.top+pos.height+this.options.offset,left:pos.left+pos.width/2-actualWidth/2}
break
case'above':tp={top:pos.top-actualHeight-this.options.offset,left:pos.left+pos.width/2-actualWidth/2}
break
case'left':tp={top:pos.top+pos.height/2-actualHeight/2,left:pos.left-actualWidth-this.options.offset}
break
case'right':tp={top:pos.top+pos.height/2-actualHeight/2,left:pos.left+pos.width+this.options.offset}
break}
$tip.css(tp).addClass(placement).addClass('in')}},setContent:function(){var $tip=this.tip()
$tip.find('.twipsy-inner')[this.options.html?'html':'text'](this.getTitle())
$tip[0].className='twipsy'},hide:function(){var that=this,$tip=this.tip()
$tip.removeClass('in')
function removeElement(){$tip.remove()}
$.support.transition&&this.$tip.hasClass('fade')?$tip.bind(transitionEnd,removeElement):removeElement()},fixTitle:function(){var $e=this.$element
if($e.attr('title')||typeof($e.attr('data-original-title'))!='string'){$e.attr('data-original-title',$e.attr('title')||'').removeAttr('title')}},hasContent:function(){return this.getTitle()},getTitle:function(){var title,$e=this.$element,o=this.options
this.fixTitle()
if(typeof o.title=='string'){title=$e.attr(o.title=='title'?'data-original-title':o.title)}else if(typeof o.title=='function'){title=o.title.call($e[0])}
title=(''+title).replace(/(^\s*|\s*$)/,"")
return title||o.fallback},tip:function(){return this.$tip=this.$tip||$('<div class="twipsy" />').html(this.options.template)},validate:function(){if(!this.$element[0].parentNode){this.hide()
this.$element=null
this.options=null}},enable:function(){this.enabled=true},disable:function(){this.enabled=false},toggleEnabled:function(){this.enabled=!this.enabled},toggle:function(){this[this.tip().hasClass('in')?'hide':'show']()}}
function maybeCall(thing,ctx,args){return typeof thing=='function'?thing.apply(ctx,args):thing}
$.fn.twipsy=function(options){$.fn.twipsy.initWith.call(this,options,Twipsy,'twipsy')
return this}
$.fn.twipsy.initWith=function(options,Constructor,name){var twipsy,binder,eventIn,eventOut
if(options===true){return this.data(name)}else if(typeof options=='string'){twipsy=this.data(name)
if(twipsy){twipsy[options]()}
return this}
options=$.extend({},$.fn[name].defaults,options)
function get(ele){var twipsy=$.data(ele,name)
if(!twipsy){twipsy=new Constructor(ele,$.fn.twipsy.elementOptions(ele,options))
$.data(ele,name,twipsy)}
return twipsy}
function enter(){var twipsy=get(this)
twipsy.hoverState='in'
if(options.delayIn==0){twipsy.show()}else{twipsy.fixTitle()
setTimeout(function(){if(twipsy.hoverState=='in'){twipsy.show()}},options.delayIn)}}
function leave(){var twipsy=get(this)
twipsy.hoverState='out'
if(options.delayOut==0){twipsy.hide()}else{setTimeout(function(){if(twipsy.hoverState=='out'){twipsy.hide()}},options.delayOut)}}
if(!options.live){this.each(function(){get(this)})}
if(options.trigger!='manual'){binder=options.live?'live':'bind'
eventIn=options.trigger=='hover'?'mouseenter':'focus'
eventOut=options.trigger=='hover'?'mouseleave':'blur'
this[binder](eventIn,enter)[binder](eventOut,leave)}
return this}
$.fn.twipsy.Twipsy=Twipsy
$.fn.twipsy.defaults={animate:true,delayIn:0,delayOut:0,fallback:'',placement:'above',html:false,live:false,offset:0,title:'title',trigger:'hover',template:'<div class="twipsy-arrow"></div><div class="twipsy-inner"></div>'}
$.fn.twipsy.rejectAttrOptions=['title']
$.fn.twipsy.elementOptions=function(ele,options){var data=$(ele).data(),rejects=$.fn.twipsy.rejectAttrOptions,i=rejects.length
while(i--){delete data[rejects[i]]}
return $.extend({},options,data)}}(window.jQuery||window.ender);!function($){"use strict"
var Popover=function(element,options){this.$element=$(element)
this.options=options
this.enabled=true
this.fixTitle()}
Popover.prototype=$.extend({},$.fn.twipsy.Twipsy.prototype,{setContent:function(){var $tip=this.tip()
$tip.find('.title')[this.options.html?'html':'text'](this.getTitle())
$tip.find('.content > *')[this.options.html?'html':'text'](this.getContent())
$tip[0].className='popover'},hasContent:function(){return this.getTitle()||this.getContent()},getContent:function(){var content,$e=this.$element,o=this.options
if(typeof this.options.content=='string'){content=$e.attr(this.options.content)}else if(typeof this.options.content=='function'){content=this.options.content.call(this.$element[0])}
return content},tip:function(){if(!this.$tip){this.$tip=$('<div class="popover" />').html(this.options.template)}
return this.$tip}})
$.fn.popover=function(options){if(typeof options=='object')options=$.extend({},$.fn.popover.defaults,options)
$.fn.twipsy.initWith.call(this,options,Popover,'popover')
return this}
$.fn.popover.defaults=$.extend({},$.fn.twipsy.defaults,{placement:'right',content:'data-content',template:'<div class="arrow"></div><div class="inner"><h3 class="title"></h3><div class="content"><p></p></div></div>'})
$.fn.twipsy.rejectAttrOptions.push('content')}(window.jQuery||window.ender);!function($){"use strict"
$.fn.dropdown=function(selector){return this.each(function(){$(this).delegate(selector||d,'click',function(e){var li=$(this).parent('li'),isActive=li.hasClass('open')
clearMenus()!isActive&&li.toggleClass('open')
return false})})}
var d='a.menu, .dropdown-toggle'
function clearMenus(){$(d).parent('li').removeClass('open')}
$(function(){$('html').bind("click",clearMenus)
$('body').dropdown('[data-dropdown] a.menu, [data-dropdown] .dropdown-toggle')})}(window.jQuery||window.ender);