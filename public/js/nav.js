   $(document).ready(function () {
        var url = window.location;

    // Atrapa las opciones que no tienen hijos y le asigna a la clase el estado activo
       
       $('ul.nav a[href="' + url + '"]').parent().addClass('active');

    // Lo mismo pero con los hijos, en caso de dropdown por ejemplo. La de arriba no funciona si es desplegable.
        $('ul.nav a').filter(function () {
            return this.href == url;
        }).parent().addClass('active').parent().parent().addClass('active');
    });