// import '../../../resources/js/app.js';

setTimeout(() => {
    window.SpeechRecognitionAlternative.channel('testing')
    .listen('.App\\Events\\testWebsocket',(e)=>{
        console.log(e);
    })
}, 200);