<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
<script src="{{ asset('assets/js/plugins.js') }}"></script>

<!-- apexcharts -->
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>

<!-- Vector map-->
<script src="{{ asset('assets/libs/jsvectormap/jsvectormap.min.js') }}"></script>
<script src="{{ asset('assets/libs/jsvectormap/maps/world-merc.js') }}"></script>

<!--Swiper slider js-->
<script src="{{ asset('assets/libs/swiper/swiper-bundle.min.js') }}"></script>

<!-- Dashboard init -->
<script src="{{ asset('assets/js/pages/dashboard-ecommerce.init.js') }}"></script>

<!-- App js -->
<script src="{{ asset('assets/js/app.js') }}"></script>

{{-- jquery --}}
<script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>

{{-- DataTable --}}
<script src="https://cdn.datatables.net/2.3.0/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.0/js/dataTables.bootstrap5.min.js"></script>

{{-- Chart JS CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

{{-- Select2 --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

{{-- Izi Toast --}}
<script src="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/js/iziToast.min.js"></script>

{{-- Quill JS --}}
<script src="{{ asset('assets/libs/quill/quill.min.js') }}"></script>

{{-- Dropzone JS --}}
<script src="{{ asset('assets/libs/dropzone/dropzone-min.js') }}"></script>

<script>
    var BASE_URL = "{{ url('/') }}";
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {
        $.extend(true, $.fn.dataTable.defaults, {
            language: {
                emptyTable: "Belum ada data",
                zeroRecords: "Data tidak ditemukan",
                loadingRecords: "Loading...",
                processing: "Processing...",
            }
        });
    });

    iziToast.settings({
        position: 'topRight',
        topOffset: 150,
        timeout: 3000,
        progressBar: true,
        closeOnClick: true,
        pauseOnHover: true,
    });

    $(document).on('click', '.js-copy-kode-kegiatan', function() {
        var text = $(this).data('copy-text') || $(this).text().trim();

        if (!text) {
            return;
        }

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(function() {
                iziToast.success({
                    message: 'Copied to clipboard'
                });
            }).catch(function() {
                fallbackCopy(text);
            });
        } else {
            fallbackCopy(text);
        }
    });

    function fallbackCopy(text) {
        var $temp = $('<textarea>');
        $('body').append($temp);
        $temp.val(text).select();

        try {
            document.execCommand('copy');
            iziToast.success({
                message: 'Copied to clipboard'
            });
        } catch (e) {
            iziToast.error({
                message: 'Gagal copy clipboard'
            });
        }

        $temp.remove();
    }
</script>
