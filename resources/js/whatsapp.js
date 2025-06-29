import './bootstrap';
import './echo';
import jQuery from 'jquery'
window.$ = jQuery;

/* const channel = Echo.channel('Test-channel');
channel.listen('TestEvent', (e) => {
    console.log(e.message);
}); */

/* const channel = Echo.channel('WhatsApp-channel');
channel.listen('WhatsappEvent', (e) => {
    setH1(e);
}); */

//window.Echo.channel('WhatsApp-channel').listen('WhatsappEvent', (e) => {$("h2").html(e)});
/*
$(function(){ //Este no esta funcionando, revisar el por que ***************
    window.Echo.channel('WhatsApp-channel').listen('WhatsappEvent', (e) => {$("h2").html(e)});
}); */

function setH1(e){
    // 1. Contar cuantos h1 hay
    var h1Count = document.querySelectorAll('h1').length;
    var nuevoH1 = document.createElement('h1');
    nuevoH1.id ="h1"+h1Count++;
    nuevoH1.innerHTML = e.message;
    document.body.appendChild(nuevoH1);
    var nuevoH1 = '';
    document.createElement('br');
}
