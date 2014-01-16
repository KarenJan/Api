$(function(){
    //Search
    $('#mainSearchBar input[type="text"]').on('focus', function(){
        $(this).parent().css('border', '1px solid #999');
    });
    $('#mainSearchBar input[type="text"]').live('blur', function(){
        $(this).parent().css('border', '1px solid #cfcfcf');
    });
})

Api.endLoading();
