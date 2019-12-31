<extend name="Public:base" />
<block name="css">
    <css href="__PUBLIC__/css/bootstrap-datetimepicker.min.css"/>
    <style>
        @media (max-width: 1000px) {
            .container-sc > div > .pull-right.ml-10 {
                float: none !important;
                text-align: right;
            }
            #example_wrapper{overflow-x: auto;overflow-y: hidden;}
        }
    </style>
</block>
<block name="title">{%$Think.lang.loginTitle%}</block>
<block name="js">
<script>

var _hmt = _hmt || [];

(function() {

  var hm = document.createElement("script");

  hm.src = "https://hm.baidu.com/hm.js?ddf8b7b1dcceb762ab5a1ec4c9f230f0";

  var s = document.getElementsByTagName("script")[0];

  s.parentNode.insertBefore(hm, s);

})();

</script>

    <script>
        TIME_OUT="";
        $(function ($) {
            $(".btn-back").on("click",function () {
                window.history.go(-1);
            });
            if($("#top-open-title").hasClass("active")){
                $("#top-open-title").stop().slideDown(200,function () {
                    TIME_OUT = setTimeout(function () {
                        $("#top-open-title").stop().slideUp(200);
                    },5000);
                });
            }
            $("#top-open-title").on("click",function () {
                clearTimeout(TIME_OUT);
                $("#top-open-title").stop().slideUp(200);
            });
        });
        function openTopTitleDiv($str) {
            clearTimeout(TIME_OUT);
            $("#top-open-title").html($str).stop().slideDown(200,function () {
                TIME_OUT = setTimeout(function () {
                    $("#top-open-title").stop().slideUp(200);
                },5000);
            });
        }
    </script>
</block>
<block name="main">
    {__CONTENT__}
</block>
<block name="footer">
    <div id="top-open-title" class="top-open-title <notempty name='top_title'>active</notempty>">
        <span>{%$top_title|default=''%}</span>
    </div>
</block>