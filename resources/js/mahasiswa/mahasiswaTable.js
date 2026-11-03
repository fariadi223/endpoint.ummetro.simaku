/**
 * Page Mahasiswa
 */

'use strict';

$(function () {
    var dt_mhs_table = $('.dt-table-mahasiswa');
    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    if (dt_mhs_table.length) {
        var dt_mahasiswa = dt_mhs_table.DataTable({
            processing: true,
            serverSide: true,
            ajax: {
              url: baseUrl + 'dataTableJson/mahasiswa'
            },
            columns: [
                // columns according to JSON
                { data: '' },
                { data: 'nipd' },
                { data: 'tgl_lahir' },
                { data: 'action' }
            ],
            order: [[1, 'desc']],
            columnDefs: [
                {
                  // For Responsive
                  className: 'control',
                  searchable: false,
                  orderable: false,
                  responsivePriority: 2,
                  targets: 0,
                  render: function (data, type, full, meta) {
                    return '';
                  }
                },
                {
                    // Actions
                    targets: -1,
                    title: 'Actions',
                    searchable: false,
                    orderable: false,
                    render: function (data, type, full, meta) {
                      return '';
                    }
                },
                {
                  // Client name and Service
                  targets: 1,
                  responsivePriority: 4,
                  render: function (data, type, full, meta) {

                    var $service = full.nipd,
                           $name = full.nm_pd,
                          $image = true, /*full['avatar_image'], */
                       $rand_num = Math.floor(Math.random() * 11) + 1,
                       $user_img = $rand_num + '.png';
                    
                    if ($image === true) {
                      // For Avatar image
                      var $output =
                        '<img src="' + assetsPath + 'img/avatars/' + $user_img + '" alt="Avatar" class="rounded-circle">';
                    } else {
                      // For Avatar badge
                      var stateNum = Math.floor(Math.random() * 6),
                            states = ['success', 'danger', 'warning', 'info', 'primary', 'secondary'],
                            $state = states[stateNum],
                             $name = full['client_name'],
                         $initials = $name.match(/\b\w/g) || [];

                      $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
                      $output = '<span class="avatar-initial rounded-circle bg-label-' + $state + '">' + $initials + '</span>';
                    }
                    // Creates full output for row
                    var $row_output =
                      '<div class="d-flex justify-content-start align-items-center">' +
                        '<div class="avatar-wrapper">' +
                          '<div class="avatar avatar-sm me-2">' +
                          $output +
                          '</div>' +
                        '</div>' +
                        '<div class="d-flex flex-column">' +
                          '<a href="' +  baseUrl +  'pages/profile-user" class="text-body text-truncate"><span class="fw-semibold">' + $name + '</span></a>' +
                          '<small class="text-truncate text-muted">' + $service +'</small>' +
                        '</div>' +
                      '</div>';
                    return $row_output;
                  }
                },
                {
                  targets: 2,
                  searchable: false,
                  orderable: false,
                  render: function (data, type, full, meta) {
                    var tglLhr = moment(full.tgl_lahir, "YYYY-MM-DD", 'id').format("DD-MMM-YYYY");
                    var $row_output =
                      '<div class="d-flex justify-content-start align-items-center">' +
                      ' <div class="d-flex flex-column">' +
                          '<span class="fw-semibold">' + full.tmpt_lahir + '</span>' +
                          '<small class="text-truncate text-muted"> Tanggal ' + tglLhr +'</small>' +
                        '</div>' +
                      '</div>';
                    return $row_output;
                    return moment(full.tgl_lahir, "YYYY-MM-DD", 'id').format("DD-MMM-YYYY");
                  }
                },
            ],
            dom:
            '<"row me-2"' +
            '<"col-md-2"<"me-3"l>>' +
            '<"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0"fB>>' +
            '>t' +
            '<"row mx-2"' +
            '<"col-sm-12 col-md-6"i>' +
            '<"col-sm-12 col-md-6"p>' +
            '>',
            language: {
                sLengthMenu: '_MENU_',
                search: '',
                searchPlaceholder: 'Search..'
            },
            buttons: [
              {
                text: '<i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">Add New User</span>',
                className: 'modal-add-mhs btn btn-primary mx-3',
                attr: {
                  'data-bs-toggle': 'modal',
                  'data-bs-target': '#modal-add-mhs'
                }
              }
            ],
        });
    }

    setTimeout(() => {
        $('.dataTables_filter .form-control').removeClass('form-control-sm');
        $('.dataTables_length .form-select').removeClass('form-select-sm');

        /*
        $('#modal-add-mhs').on('hidden.bs.modal', function () {
          $(this).find('form').trigger('reset');
        })
        */
    }, 300);
});

