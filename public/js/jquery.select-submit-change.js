;(function($) {
  $.fn.SelectSubmitChange = function(){
    $(this).change(function(){
      //$("#submit_form"+$(this).attr("id").slice(-1)).submit();
      $("#submit_form").submit();
    });
  }
})(jQuery);
