<x-app-layout>
    @push('css')
        <style>
            #reader__scan_region{
                display: flex;
                justify-content: center; /* Memposisikan elemen secara horizontal di tengah halaman */
                align-items: center; /* Memposisikan elemen secara vertikal di tengah halaman */
            }
        </style>
    @endpush
    @push('js')
    {{-- <script src="{{ asset('js/instascan.min.js') }}"></script> --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        function onScanSuccess(decodedText, decodedResult) {
            // Handle on success condition with the decoded text or result.
            console.log(`Scan result: ${decodedText}`, decodedResult);
            let url = `{{ route('verifikasi.read') }}`
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    no_antrian:decodedText,
                },
                success:function(data){
                    console.log(data);
                    if (data == 'error') {
                        swal("Error!", "There was an error processing your request.", "error");
                    }else{
                        window.location.href = `{{ route('laporan.kunjungan-jenis') }}`;
                    }
                }
            })
        }

        function onScanError(errorMessage) {
            // handle on error condition, with error message
        }

        var html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", { fps: 16, qrbox: 350 });
        html5QrcodeScanner.render(onScanSuccess,onScanError);
    </script>
    @endpush
    <div class="p-4 sm:ml-64 pt-20 h-screen">
        <section class="p-5 overflow-y-auto mt-5">
            <div class="head lg:flex grid grid-cols-1 justify-between w-full">
                <div class="heading flex-auto">
                    <p class="text-blue-950 font-sm text-xs">
                        Transaksi
                    </p>
                    <h2 class="font-bold tracking-tighter text-2xl text-theme-text">
                        {{ $title }}
                    </h2>
                </div>
            </div>
            <div class="card bg-white p-5 mt-4 border rounded-md w-full relative overflow-x-auto">
                <div id="reader" width="800px"></div>
            </div>
        </section>
    </div>
</x-app-layout>
