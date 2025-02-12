function ajaxform(url,method,data,success,error){

$.ajax({
    url:url,
    method:method,
    data:data,
    headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
            },

    success:success,
    error:error

});
}
export default ajaxform
