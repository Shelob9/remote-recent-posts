jQuery( document ).ready( function ( $ ) {

    var elements = $( '.jp-remote-post-widget' );
    if( 0 < elements.length ){
        var key, element;
        $.each( function( widgets, i ) {
            element = $( this );
            key = $( this ).attr( 'data-key' );
            $.ajax({
                url: JP_REMOTE_WIDGET.url,
                data: {
                    key: key,
                    action: 'jp_remote_widget',
                    nonce: JP_REMOTE_WIDGET.nonce
                },
                complete: function( response ){
                    element.innerHTML = response;
                }
            });
        });
    }
} );
