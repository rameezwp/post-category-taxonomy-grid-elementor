jQuery(document).ready(function($) {
    // Upload thumbnail button
    $('#upload_thumbnail_button').click(function() {
        var send_attachment_bkp = wp.media.editor.send.attachment;
        wp.media.editor.send.attachment = function(props, attachment) {
            $('#pcge_thumbnail_id').val(attachment.id);
            $('#thumbnail_preview').html('<img src="' + attachment.url + '" style="max-width:100%;"/>');
            wp.media.editor.send.attachment = send_attachment_bkp;
        };
        wp.media.editor.open();
        return false;
    });

    // Remove thumbnail button
    $('#remove_thumbnail_button').click(function() {
        $('#pcge_thumbnail_id').val('');
        $('#thumbnail_preview').html('');
    });
});