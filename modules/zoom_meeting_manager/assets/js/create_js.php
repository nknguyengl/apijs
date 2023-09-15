<script>
    $(function() {
        'use strict'

        appValidateForm($("form[name='meeting-form']"), {
            topic: "required",
            date: "required",
        }, beforeSubmit);

        function beforeSubmit(form) {
            form.submit();
            $('#btnScheduleMeeting').prop('disabled', true).html("<?= _l('wait_text'); ?>" + ' <i class="fa fa - refresh fa - spin fa - fw "></i>');
        }

        $(".reveal").on('click', function() {
            var pwd_field = $(".pwd");
            (pwd_field.attr('type') === 'password') ?
            pwd_field.attr('type', 'text'): pwd_field.attr('type', 'password');
        });

        init_selectpicker();
        // Menu
        $('.menu-item-zoom_meeting_manager').toggleClass('active');

        // Items ajax search
        init_ajax_search('items', '#item_select.ajax-search', undefined, admin_url + 'items/search');

        // Leads 
        var _rel_id = $('#rel_id'),
            _rel_type = $('#rel_lead_type'),
            serverData = {};

        serverData.rel_id = _rel_id.val();
        init_ajax_search(_rel_type.val(), _rel_id, serverData);
        // Contacts
        var _rel_contact_id = $('#rel_contact_id'),
            _rel_contact_type = $('#rel_contact_type'),
            serverDataContact = {};

        serverDataContact.rel_id = _rel_contact_id.val();
        init_ajax_search(_rel_contact_type.val(), _rel_contact_id, serverDataContact);

    });
</script>