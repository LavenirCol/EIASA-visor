function saveConfigTickets()
{
    var data =  new Object();   
    data.configList = new Array();    
    $("input[type=checkbox]:checked").each(function()
    {
        //var item = new Object();
        //item.config = 
        data.configList.push($(this).attr("name"));        
    });
   
    $.ajax(
        {'method' :'PUT',
         'url'  : "/config/updateconfigtickets",
         'cache':false,
         'data' : data,
         'contentType': 'application/json',
         'dataType': 'json',
         'success': (function(data)
         {
            console.log(data);
         }),
     });
}