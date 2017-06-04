function Core(){
  var SiteUrl; 
  this.SiteUrl = window.location.origin;
}


Core.prototype.ajax = function (url,datas,successCallback,failedCallback) {
    var type = (typeof datas === 'undefined' || !datas) ? 'GET' : 'POST';


    var errorFunction = function(){
        alert('An error has occured please try again');
    }

    if(typeof failedCallback === 'undefined' || !failedCallback){
        failedCallback = errorFunction;
    }

    var master = this;

	$.ajax({
        url:		url,
        type:		type,
		data:		datas,
        success:	function(data, textStatus, jqXHR) {
						if (data.operation == 'success') {
							successCallback(data, textStatus, jqXHR);
						} else if (data.operation == 'failed') {
							failedCallback(data, textStatus, jqXHR);
						}
					},
        error:		errorFunction
    });
}
