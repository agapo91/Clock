$().ready(function () {
    let link = $('#buttonaction');
    const baseURL = link.attr('href');
    $('#deleteBlogModal').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget);
        let id = button.data('id');
        link.attr('href', baseURL + id);
    });
    $( window ).scroll(function() {
        $nav = $('nav.navbar');
        if(this.scrollY > 70 && ($nav.hasClass('logged'))) {
            $nav.addClass('scrolled');
        } else {
            $nav.removeClass('scrolled');
        }
    });
});
