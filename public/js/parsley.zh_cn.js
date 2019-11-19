/**
* /!\ This file is just an example template to create/update your own language file /!\
*/

window.ParsleyConfig = window.ParsleyConfig || {};

(function ($) {
  window.ParsleyConfig = $.extend( true, {}, window.ParsleyConfig, {
    messages: {
      // parsley //////////////////////////////////////
        defaultMessage: "不正确的值"
        , type: {
            email:      "请输入正确的电子邮件"
          , url:        "请输入正确的URL"
          , urlstrict:  "请输入正确的URL"
          , number:     "请输入合法的数字"
          , digits:     "请输入单独的数字"
          , dateIso:    "请输入正确的日期描述(YYYY-MM-DD)."
          , alphanum:   "只能输入字母和数字"
        }
      , notnull:        "不可为null"
      , notblank:       "不可为空"
      , required:       "必填"
      , regexp:         "值不合法"
      , min:            "值应该大于 %s"
      , max:            "值应该小于 %s."
      , range:          "值应该大于 %s 并小于 %s."
      , minlength:      "必须输入 %s 个以上的文字"
      , maxlength:      "长度应小于等于 %s 个文字"
      , rangelength:    "长度应介于 %s 和 %s 个文字之间"
      , mincheck:       "你至少要选择 %s 个选项"
      , maxcheck:       "你最多只能选择 %s 个选项"
      , rangecheck:     "你只能选择 %s 到 %s 个选项"
      , equalto:        "字段值应该和给定的值一样"

      // parsley.extend ///////////////////////////////
      , minwords:       "字段值应该至少有 %s 个词"
      , maxwords:       "字段值最多只能有 %s 个词"
      , rangewords:     "字段值应该有 %s 到 %s 个词"
      , greaterthan:    "字段值应该大于 %s"
      , lessthan:       "字段值应该小于 %s"
      , beforedate:     "字段值所表示的日期应该早于 %s."
      , afterdate:      "字段值所表示的日期应该晚于 %s."
    }
  });
}(window.jQuery || window.Zepto));
