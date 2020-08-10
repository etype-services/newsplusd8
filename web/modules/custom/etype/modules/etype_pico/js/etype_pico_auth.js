$(document).ready(function(){
    const encryption = new Encryption();
    const redirect = encryption.decrypt('{{ url }}', '{{ salt }}');
    window.addEventListener('pico.loaded', function() {
        console.log(window.Pico.user.verified);
        console.log(redirect);
        if (window.Pico.user.verified == true) {
            window.location.replace(redirect);
        }
    });
});