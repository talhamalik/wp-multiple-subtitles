console.log('ready for add more click event');
// btn-gi-add-more-input
jQuery( document ).ready( function( $ ) {
    $(document).on('click','.btn-gi-add-more-subtitle',function(e){
        e.preventDefault();
        console.log('btn clicked to add btn of a=more title');
        $('.gi-subtitles-container').append('<div id="subtitlewrap" class="gi-input-container"><input type="text" id="gi_wpsubtitle" name="gi_wp_subtitles[]" value="" autocomplete="off" placeholder="Enter subtitle here"></div>');
    });
});
