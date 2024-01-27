setTimeout(() => {
    window.Echo.channel('testing')
    .listen('.App\\Events\\testeWebsocket',(e)=>{
        console.log(e);
    })
}, 1000);