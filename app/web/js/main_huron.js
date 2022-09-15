/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/* 
    Created on : 20/02/2014, 11:04:07 AM
    Author     : Diego Martinez AKA Huron :)
*/ 
var isMobile = {
			mobilecheck: function() {
				return (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino|android|ipad|playbook|silk/i.test(navigator.userAgent || navigator.vendor || window.opera) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test((navigator.userAgent || navigator.vendor || window.opera).substr(0, 4)))
			}
                    }
  var tamano=0.7;
  
    
function Init(page){
    
    var p = page || 'default';
    
    if(p === 'index/secretaria' || p === 'index/especialista' || p === 'index/contador' || p === 'index/admin'){
        $('.menuvertical li a[href="/historia_clinica/indexsinid"]').attr('href','javascript:HistoriaClinica()');
    }
    
    if(p === 'index/especialista'){
        $('#menu-doc-paciente').fadeIn();
    }
    
    if(p === 'login'){
        $('div.sidebar').remove();
        $('div.main-sub').removeClass('col-sm-10 col-md-10').addClass('col-sm-12 col-md-12');
    }
    
    var paginasExcluidasDeTablas = ["paciente_procedimiento", "calendario"];
    
    // No agregar las clases automaticamente a las paginas excluidas
    if(paginasExcluidasDeTablas.indexOf(p) === -1){
        $('table').attr({'width':'','heigth':'','cellpadding':'','cellspacing':''}).removeClass('dataTable no-footer');
        //$('table tr').attr({'border':'','width':'','heigth':'','style':''});
    }
    
    if(p !== 'calendario'){
        
        $.reject({  
            reject: {  
                //safari: true, // Apple Safari  
                //chrome: true, // Google Chrome  
                //firefox: true, // Mozilla Firefox  
                msie: true, // Microsoft Internet Explorer  
                opera: true, // Opera  
                konqueror: true, // Konqueror (Linux)  
                unknown: true
            },
            display: ['firefox','chrome'], // Displays only firefox, chrome, and opera
            header: 'Le recomendamos usar otro navegador', // Header Text
            paragraph1: 'El navegador que usa actualmente no es compatible con todas las características de la aplicación', // Paragraph 1
            paragraph2: 'Por favor instale uno de los siguientes navegadores para continuar',
            closeMessage: '¡Si continúa puede que hayan fallos en la aplicación!', // Message below close window link
            //closeCookie: true,
            closeLink: 'Omitir y continuar'
        });
        
        $.pnotify.defaults.history = false;
        $.pnotify.defaults.styling = "jqueryui";
        window.alert = function(message,typer) {
           var tipo = typer || 'error';
           $.pnotify({               
               text: message,
               type: tipo
           });
        };
        
        $('.menuvertical li a').each(function(){
            var el = $(this);
            var text = el.find('small').html();
            if(text !== undefined){
                var timer = false;
                el.mouseenter(function(){
                   timer = setTimeout(function(){
                        el.popover({
                        html: true,
                        content: text,
                        container: 'body'
                    }).popover('show');
                   },2000); 
                });
                el.mouseleave(function(){
                   clearTimeout(timer);
                   el.popover('destroy');
                });
            }
        });
    }
    
    $(".main-title").fitText(3.2);
    
    $('input[type=text], input[type=password], input[type=textarea], input:not([type]), select').addClass('form-control');
    $('form').addClass('form');
    $('button:not(.navbar-toggle), input[type=submit]').each(function(){
        if(!($(this).hasClass('btn'))){$(this).addClass('btn');};
        if(!($(this).hasClass('btn-success')) && !($(this).hasClass('btn-primary')) && !($(this).hasClass('btn-success')) && !($(this).hasClass('btn-warning')) && !($(this).hasClass('btn-danger')) && !($(this).hasClass('btn-info'))){$(this).addClass('btn-info');};
    });
    
    //toggle side nav off-canvas
    $('[data-toggle=offcanvas]').click(function () {
        $('.row-offcanvas').toggleClass('active')
    });

    
    $('textarea').css('width','100%').addClass('form-control');

}
/** Add Event **/

function addEvent(elm, evType, fn, useCapture) {
	if (elm.addEventListener) { 
		elm.addEventListener(evType, fn, useCapture); 
		return true; 
	}else if (elm.attachEvent) { 
		var r = elm.attachEvent('on' + evType, fn); 
		return r; 
	}else {
		elm['on' + evType] = fn;
	}
}


    
    


//Check empty input value by javascript
function checkInput(e){
    if(e.val() === ''){
        if(e.parent().parent().hasClass('control-group')){
            e.parent().parent().addClass('error');
            if((e.parent().find('.help-inline').length) === 0){
               e.parent().append('<span class="help-inline">Campo obligatorio</span>');
            } 
        }else{
            e.wrap('<div class="control-group error" />').wrap('<div class="controls" />');
            e.parent().append('<span class="help-inline">Campo obligatorio</span>');
        }
        e.focus(function(){
           e.parent().parent().removeClass('error');
           e.parent().find('.help-inline').remove();
        });
        e.parent().focus();
        return false;
    }else{
        return true;
    }
}

function b64toBlob(b64Data, contentType, sliceSize) {
        contentType = contentType || '';
        sliceSize = sliceSize || 512;

        var byteCharacters = atob(b64Data);
        var byteArrays = [];

        for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
            var slice = byteCharacters.slice(offset, offset + sliceSize);

            var byteNumbers = new Array(slice.length);
            for (var i = 0; i < slice.length; i++) {
                byteNumbers[i] = slice.charCodeAt(i);
            }

            var byteArray = new Uint8Array(byteNumbers);

            byteArrays.push(byteArray);
        }

      var blob = new Blob(byteArrays, {type: contentType});
      return blob;
}

 (function($) {
        $.fn.validCampoFranz = function(cadena) {
            $(this).on({
                keypress: function(e) {
                    var key = e.which,
                        keye = e.keyCode,
                        tecla = String.fromCharCode(key).toLowerCase(),
                        letras = cadena;
                    if (letras.indexOf(tecla) == -1 && keye != 9 && (key == 37 || keye != 37) && (keye != 39 || key == 39) && keye != 8 && (keye != 46 || key == 46) || key == 161) {
                        e.preventDefault();
                    }
                }
            });
        };
    })(jQuery);