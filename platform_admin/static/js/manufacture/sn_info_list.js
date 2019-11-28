(function () {
    let log_module = $("#log-module");

    log_module.find('.box-header').on('click', 'span', function () {
        log_module.hide();
    });

    $('.log-detail').on('click', 'span', log_detail);

    function log_detail() {
        let self = $(this),
            sn = self.data('sn');
        log_module.find('table').find('tbody').empty();

        $.ajax({
            url: SN_BIND_LOG_URL,
            type: 'post',
            dataType: 'json',
            data: {sn:sn},
            success: function (data) {
                let tb = log_module.find('table').find('tbody');
                $.each(data.data, function (i, v) {
                    let type = v.bind_type== 0?"绑定":"解绑", uuid = v.uuid?v.uuid:"-";

                    tb.append('<tr><td>'+uuid+'</td><td>'+type+'</td><td><span class="badge bg-red">'+v.created_ts+'</span></td></tr>');
                });
                log_module.show();
            }
        });

    }



})();