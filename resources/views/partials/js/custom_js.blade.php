<script>

    function getLGA(state_id){
               var url = "{{ url('ajax/get_lga') }}/" + encodeURIComponent(state_id);
        var lga = $('#lga_id');

        $.ajax({
            dataType: 'json',
            url: url,
            success: function (resp) {
                //console.log(resp);
                lga.empty();
                $.each(resp, function (i, data) {
                    lga.append($('<option>', {
                        value: data.id,
                        text: data.name
                    }));
                });

            }
        })
    }

    function getClassSections(class_id, destination){
        var url = '{{ route('get_class_sections', [':id']) }}';
        url = url.replace(':id', class_id);
        var section = destination ? $(destination) : $('#section_id');

        $.ajax({
            dataType: 'json',
            url: url,
            success: function (resp) {
                //console.log(resp);
                section.empty();
                $.each(resp, function (i, data) {
                    section.append($('<option>', {
                        value: data.id,
                        text: data.name
                    }));
                });

            }
        })
    }

    function getClassSubjects(class_id){
        var url = '{{ route('get_class_subjects', [':id']) }}';
        url = url.replace(':id', class_id);
        var section = $('#section_id');
        var subject = $('#subject_id');

        $.ajax({
            dataType: 'json',
            url: url,
            success: function (resp) {
                console.log(resp);
                section.empty();
                subject.empty();
                $.each(resp.sections, function (i, data) {
                    section.append($('<option>', {
                        value: data.id,
                        text: data.name
                    }));
                });
                $.each(resp.subjects, function (i, data) {
                    subject.append($('<option>', {
                        value: data.id,
                        text: data.name
                    }));
                });

            }
        })
    }


    {{-- Notifications: in-app alerts always work; browser alerts are opt-in. --}}
    @php
    $serverNotifications = [
        'pop_error' => session('pop_error'),
        'pop_warning' => session('pop_warning'),
        'pop_success' => session('pop_success'),
        'flash_info' => session('flash_info'),
        'flash_success' => session('flash_success'),
        'flash_warning' => session('flash_warning'),
        'flash_error' => session('flash_error') ?: session('flash_danger'),
    ];
    @endphp
    var serverNotifications = {!! json_encode($serverNotifications, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!};

    (function () {
        var notificationStack;
        var permissionButton = document.getElementById('notification-permission-toggle');
        var browserNotificationsKey = 'spams.browserNotifications.enabled';
        var browserNotificationsEnabled = false;
        var iconByType = { success: '\u2713', info: 'i', warning: '!', danger: '\u00d7' };

        try {
            browserNotificationsEnabled = window.localStorage.getItem(browserNotificationsKey) === 'true';
        } catch (error) {
            browserNotificationsEnabled = false;
        }

        function getStack() {
            if (!notificationStack) {
                notificationStack = document.createElement('div');
                notificationStack.className = 'app-notification-stack';
                notificationStack.setAttribute('aria-live', 'polite');
                notificationStack.setAttribute('aria-relevant', 'additions');
                notificationStack.setAttribute('aria-label', 'Notifications');
                document.body.appendChild(notificationStack);
            }
            return notificationStack;
        }

        function updatePermissionButton() {
            if (!permissionButton) return;

            var label = permissionButton.querySelector('.notification-settings-label');
            var supported = 'Notification' in window;
            var enabled = supported && Notification.permission === 'granted' && browserNotificationsEnabled;

            permissionButton.setAttribute('aria-pressed', enabled ? 'true' : 'false');
            permissionButton.title = enabled ? 'Browser notifications are enabled' : 'Enable browser notifications';
            if (label) label.textContent = enabled ? 'Alerts on' : 'Enable alerts';
        }

        function showBrowserNotification(type, message) {
            if (!browserNotificationsEnabled || !('Notification' in window) || Notification.permission !== 'granted') return;

            try {
                var browserAlert = new Notification(type === 'success' ? 'Task completed' : 'Task needs attention', { body: message });
                browserAlert.onclick = function () {
                    window.focus();
                    browserAlert.close();
                };
            } catch (error) {
                // The in-app notification remains available if the browser blocks desktop alerts.
            }
        }

        window.flash = function (data) {
            data = data || {};
            var message = data.msg == null ? '' : String(data.msg);
            if (!message) return;

            var type = data.type === 'error' ? 'danger' : data.type;
            if (!iconByType[type]) type = 'info';

            var toast = document.createElement('div');
            toast.className = 'app-toast app-toast--' + type;
            toast.setAttribute('role', type === 'danger' ? 'alert' : 'status');

            var icon = document.createElement('span');
            icon.className = 'app-toast-icon';
            icon.setAttribute('aria-hidden', 'true');
            icon.textContent = iconByType[type];

            var text = document.createElement('div');
            text.className = 'app-toast-message';
            text.textContent = message;

            var close = document.createElement('button');
            close.className = 'app-toast-close';
            close.type = 'button';
            close.setAttribute('aria-label', 'Dismiss notification');
            close.textContent = '\u00d7';
            close.addEventListener('click', function () { toast.remove(); });

            toast.appendChild(icon);
            toast.appendChild(text);
            toast.appendChild(close);
            getStack().appendChild(toast);

            window.setTimeout(function () {
                if (toast.parentNode) toast.remove();
            }, type === 'danger' ? 9000 : 6000);

            if (type === 'success' || type === 'danger' || type === 'warning') showBrowserNotification(type, message);
            window.dispatchEvent(new CustomEvent('app:notification', { detail: { type: type, message: message } }));
        };

        window.pop = function (data) {
            data = data || {};
            window.flash({ msg: data.msg, type: data.type === 'success' ? 'success' : (data.type === 'warning' ? 'warning' : 'danger') });
        };

        if (permissionButton) {
            permissionButton.addEventListener('click', function () {
                if (!('Notification' in window)) {
                    window.flash({ msg: 'Browser notifications are not supported here. In-app alerts will still appear.', type: 'info' });
                    return;
                }

                if (Notification.permission === 'granted') {
                    browserNotificationsEnabled = !browserNotificationsEnabled;
                    try { window.localStorage.setItem(browserNotificationsKey, browserNotificationsEnabled ? 'true' : 'false'); } catch (error) {}
                    updatePermissionButton();
                    window.flash({ msg: browserNotificationsEnabled ? 'Browser alerts enabled for important task updates.' : 'Browser alerts turned off. In-app alerts will continue.', type: 'info' });
                    return;
                }

                if (Notification.permission === 'denied') {
                    window.flash({ msg: 'Browser alerts are blocked in your browser settings. In-app alerts will continue to appear.', type: 'warning' });
                    return;
                }

                var permissionRequest;
                try {
                    permissionRequest = Notification.requestPermission();
                } catch (error) {
                    window.flash({ msg: 'Browser alerts could not be enabled. In-app alerts will continue to appear.', type: 'warning' });
                    return;
                }

                Promise.resolve(permissionRequest).then(function (permission) {
                    browserNotificationsEnabled = permission === 'granted';
                    try { window.localStorage.setItem(browserNotificationsKey, browserNotificationsEnabled ? 'true' : 'false'); } catch (error) {}
                    updatePermissionButton();
                    window.flash({
                        msg: browserNotificationsEnabled ? 'Browser alerts enabled for important task updates.' : 'Browser alerts were not enabled. In-app alerts will continue to appear.',
                        type: browserNotificationsEnabled ? 'success' : 'info'
                    });
                }).catch(function () {
                    window.flash({ msg: 'Browser alerts could not be enabled. In-app alerts will continue to appear.', type: 'warning' });
                });
            });
        }

        updatePermissionButton();

        [
            ['pop_error', 'danger'], ['pop_warning', 'warning'], ['pop_success', 'success'],
            ['flash_error', 'danger'], ['flash_warning', 'warning'], ['flash_info', 'info'], ['flash_success', 'success']
        ].forEach(function (entry) {
            if (serverNotifications[entry[0]]) window.flash({ msg: serverNotifications[entry[0]], type: entry[1] });
        });
    })();

    {{-- Shared mobile drawer. The theme's desktop control continues to collapse to an icon rail. --}}
    (function () {
        var sidebar = document.querySelector('.page-content > .sidebar.sidebar-main');
        if (!sidebar) return;

        var backdrop = document.createElement('div');
        backdrop.className = 'app-sidebar-backdrop';
        backdrop.setAttribute('aria-hidden', 'true');
        document.body.appendChild(backdrop);

        function closeSidebar() {
            document.body.classList.remove('sidebar-mobile-main');
            document.body.style.overflow = '';
            document.querySelectorAll('.sidebar-mobile-main-toggle').forEach(function (toggle) {
                toggle.setAttribute('aria-expanded', 'false');
            });
        }

        function openSidebar() {
            document.body.classList.add('sidebar-mobile-main');
            document.body.style.overflow = 'hidden';
            document.querySelectorAll('.sidebar-mobile-main-toggle').forEach(function (toggle) {
                toggle.setAttribute('aria-expanded', 'true');
            });
        }

        document.addEventListener('click', function (event) {
            var toggle = event.target.closest('.sidebar-mobile-main-toggle');
            if (toggle && window.matchMedia('(max-width: 767.98px)').matches) {
                event.preventDefault();
                if (toggle.closest('.sidebar') || document.body.classList.contains('sidebar-mobile-main')) closeSidebar();
                else openSidebar();
                return;
            }

            if (event.target === backdrop) {
                closeSidebar();
                return;
            }

            var menuLink = event.target.closest('.sidebar-main .nav-sidebar a.nav-link');
            if (menuLink && menuLink.getAttribute('href') && menuLink.getAttribute('href') !== '#' && window.matchMedia('(max-width: 767.98px)').matches) {
                closeSidebar();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && document.body.classList.contains('sidebar-mobile-main')) closeSidebar();
        });

        window.addEventListener('resize', function () {
            if (window.innerWidth >= 768) closeSidebar();
        });
    })();

    function confirmDelete(id) {
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this item!",
            icon: "warning",
            buttons: true,
            dangerMode: true
        }).then(function(willDelete){
            if (willDelete) {
             $('form#item-delete-'+id).submit();
            }
        });
    }

    function confirmReset(id) {
        swal({
            title: "Are you sure?",
            text: "This will reset this item to default state",
            icon: "warning",
            buttons: true,
            dangerMode: true
        }).then(function(willDelete){
            if (willDelete) {
             $('form#item-reset-'+id).submit();
            }
        });
    }

    $('form#ajax-reg').on('submit', function(ev){
        ev.preventDefault();
        submitForm($(this), 'store');
        $('#ajax-reg-t-0').get(0).click();
    });

    $('form.ajax-pay').on('submit', function(ev){
        ev.preventDefault();
        submitForm($(this), 'store');

//        Retrieve IDS
        var form_id = $(this).attr('id');
        var td_amt = $('td#amt-'+form_id);
        var td_amt_paid = $('td#amt_paid-'+form_id);
        var td_bal = $('td#bal-'+form_id);
        var input = $('#val-'+form_id);

        // Get Values
        var amt = parseInt(td_amt.data('amount'));
        var amt_paid = parseInt(td_amt_paid.data('amount'));
        var amt_input = parseInt(input.val());

//        Update Values
        amt_paid = amt_paid + amt_input;
        var bal = amt - amt_paid;

        td_bal.text(''+bal);
        td_amt_paid.text(''+amt_paid).data('amount', ''+amt_paid);
        input.attr('max', bal);
        bal < 1 ? $('#'+form_id).fadeOut('slow').remove() : '';
    });

    $('form.ajax-store').on('submit', function(ev){
        ev.preventDefault();
        submitForm($(this), 'store');
        var div = $(this).data('reload');
        div ? reloadDiv(div) : '';
    });

    $('form.ajax-update').on('submit', function(ev){
        ev.preventDefault();
        submitForm($(this));
        var div = $(this).data('reload');
        div ? reloadDiv(div) : '';
    });

    $('.download-receipt').on('click', function(ev){
        ev.preventDefault();
        $.get($(this).attr('href'));
        flash({msg : '{{ 'Download in Progress' }}', type : 'info'});
    });

    function reloadDiv(div){
        var url = window.location.href;
        url = url + ' '+ div;
        $(div).load( url );
    }

    function submitForm(form, formType){
        var btn = form.find('button[type=submit]');
        disableBtn(btn);
        var ajaxOptions = {
            url:form.attr('action'),
            type:'POST',
            cache:false,
            processData:false,
            dataType:'json',
            contentType:false,
            data:new FormData(form[0])
        };
        var req = $.ajax(ajaxOptions);
        req.done(function(resp){
            resp.ok && resp.msg
               ? flash({msg:resp.msg, type:'success'})
               : flash({msg:resp.msg, type:'danger'});
            hideAjaxAlert();
            enableBtn(btn);
            formType == 'store' ? clearForm(form) : '';
            scrollTo('body');
            return resp;
        });
        req.fail(function(e){
            if (e.status == 422){
                var errors = e.responseJSON.errors;
                displayAjaxErr(errors);
            }
           if(e.status == 500){
               displayAjaxErr([e.status + ' ' + e.statusText + ' Please Check for Duplicate entry or Contact School Administrator/IT Personnel'])
           }
            if(e.status == 404){
               displayAjaxErr([e.status + ' ' + e.statusText + ' - Requested Resource or Record Not Found'])
           }
            enableBtn(btn);
            return e.status;
        });
    }

    function disableBtn(btn){
        var btnText = btn.data('text') ? btn.data('text') : 'Submitting';
        btn.prop('disabled', true).html('<i class="icon-spinner mr-2 spinner"></i>' + btnText);
    }

    function enableBtn(btn){
        var btnText = btn.data('text') ? btn.data('text') : 'Submit Form';
        btn.prop('disabled', false).html(btnText + '<i class="icon-paperplane ml-2"></i>');
    }

    function displayAjaxErr(errors){
        $('#ajax-alert').show().html(' <div class="alert alert-danger border-0 alert-dismissible" id="ajax-msg"><button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>');
        $.each(errors, function(k, v){
            $('#ajax-msg').append('<span><i class="icon-arrow-right5"></i> '+ v +'</span><br/>');
        });
        scrollTo('body');
    }

    function scrollTo(el){
        $('html, body').animate({
            scrollTop:$(el).offset().top
        }, 2000);
    }

    function hideAjaxAlert(){
        $('#ajax-alert').hide();
    }

    function clearForm(form){
        form.find('.select, .select-search').val([]).select2({ placeholder: 'Select...'});
        form[0].reset();
    }



</script>
