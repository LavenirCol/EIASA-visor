function saveConfigTickets()
{
    var data =  new Object();   
    data.configList = new Array();
    var _csrf =  $('#form-token').val();
    data._csrf = _csrf;
    $("input[type=checkbox]:checked").each(function()
    {
        data.configList.push($(this).attr("name"));        
    });
   
    $.ajax(
        {'method' :'PUT',
         'url'  : "/config/updateconfigtickets",
         'cache':false,
         'data' : data,
         'contentType': 'application/json',
         'dataType': 'json',
     });
}