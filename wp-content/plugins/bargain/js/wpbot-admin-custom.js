//jquery-ui-tabs
(function($) {
	'use strict';
    // After the document is ready
    $(function(){
        $('#tabs').tabs();

        $(document).on('click', '.bargain_click_handle', function(e){

        	$('.bargain_click_handle').removeClass('nav-tab-active');
        	$(this).addClass('nav-tab-active');

        });

        var storedNoticeId = localStorage.getItem('qcld_Notice_set');
        var qcld_Notice_time_set = localStorage.getItem('qcld_Notice_time_set');

        var notice_current_time = Math.round(new Date().getTime() / 1000);

        if ('bargain-msg' == storedNoticeId && qcld_Notice_time_set > notice_current_time  ) {
            $('#message-bargain').css({'display': 'none'});
        }

        $(document).on('click', '.notice-dismiss', function(e){

            var currentDom = $(this);
            var currentWrap = currentDom.closest('.notice');
            currentWrap.css({'display': 'none'});
            localStorage.setItem('qcld_Notice_set', 'bargain-msg');

            var ts = Math.round(new Date().getTime() / 1000);
            var tsYesterday = ts + (24 * 3600);
            localStorage.setItem('qcld_Notice_time_set', tsYesterday);

        });

       

    });

})(jQuery);