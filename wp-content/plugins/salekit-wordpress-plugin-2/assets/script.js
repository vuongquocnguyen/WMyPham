$(function() {

    
    var id, tab = $('#tab li');
    $( document ).ready(function() {
        $('#salekit_admin').show();
        url_string = window.location.href;
        var url = new URL(url_string);
        var get_tab = url.searchParams.get("tab");
        if(get_tab){
            $('#tab li').removeClass('active-tab');
            $('#data-container div').removeClass('active');
            $('.tab'+get_tab).addClass('active-tab');
            $('#tab'+get_tab).addClass('active');
        }
        
    });
    tab.each(function(i, e) {
        $(this).click(function(eve){
            eve.preventDefault();
            
            
            
            id = $('a', this).attr('href');
            
            $('#tab li').removeClass('active-tab');
            $('#data-container div').removeClass('active');

            $(this).addClass('active-tab');
            $(id).addClass('active');

            
            url_string = window.location.href;
            var url = new URL(url_string);
            var get_tab = url.searchParams.get("tab");
            let params = new URLSearchParams(url.search);
            var data_id = $('a', this).data('tab');
            if(get_tab){
                params.delete('tab');                
                var new_url = url_string.substring(0,url_string.indexOf("?"))+'?'+params.toString()+'&tab='+data_id;
                
            } else {
                var new_url = url_string+'&tab='+data_id;
            }
            window.history.pushState('data', 'title', new_url);
            // url.substring(url.length - 5, url.length);
            // var new_url = url+'&tab='+id;
            // window.history.pushState('data', 'title', new_url);
            // new_url = url;
        });
    });
})