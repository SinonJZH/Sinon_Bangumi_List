function bangumi_del_confirm(id) {
    var banguminame = jQuery(`#${id}-name`).html();
    var ajax_url = Sinon_object.ajax_url;
    var data = {
        _ajax_nonce: Sinon_object.del_single_nonce,
        action: 'Sinon_Bangumi_Ajax_Delete_Single',
        bangumi_name: banguminame,
        bangumi_id: id
    };
    if (confirm(`你确定要删除番剧《${banguminame}》吗？`)) {
        jQuery.post(
            ajax_url,
            data,
            function (data) {
                if (data.success) {
                    alert('番剧删除成功！');
                    jQuery(`#${data.data.request}`).fadeOut()
                } else {
                    alert(`番剧删除失败！\n错误信息：${data.data.message}`);
                }
            }
        );
    }
}

function bangumi_increase_progress(id) {
    var ajax_url = Sinon_object.ajax_url;
    var data = {
        _ajax_nonce: Sinon_object.progress_increase_nonce,
        action: 'Sinon_Bangumi_Ajax_Increase_Progress',
        bangumi_id: id
    };
    jQuery.post(
        ajax_url,
        data,
        function (data) {
            var messgae_span = jQuery(`#${id}-increase_message`);
            var progress = jQuery(`#${id}-progress`);
            var button = jQuery(`#${id}-increase_button`);
            var status = jQuery(`#${id}-status`);
            var count = jQuery(`#${id}-count`);
            if (data.success) {
                messgae_span.css('color', 'green').html('√已更新').fadeIn();
            } else {
                messgae_span.css('color', 'red').html(`更新失败！错误消息：${data.data.message}`).fadeIn();
            }
            var message_fadeout = function (messgae_span) {
                messgae_span.fadeOut();
                delete(window.message_fade_id);
            }
            if (window.hasOwnProperty('message_fade_id')) {
                clearTimeout(window.message_fade_id);
                delete(window.message_fade_id);
            }
            window.message_fade_id = setTimeout(message_fadeout, 5000, messgae_span);
            progress.val(data.data.progress);
            if (data.data.btn_change) {
                button.html('设置为已追完');
            } else if (data.data.status_change) {
                status.val('2');
                progress.prop('disabled', true);
                count.prop('disabled', true);
                button.prop('disabled', true);
            } else {
                button.html('进度+1');
            }
        }
    );
}

function img_change_preview() {
    var bangumi_img = jQuery('#bangumi_img');
    var img_url = jQuery('#img_url').val();
    bangumi_img.attr('src', img_url);
}
