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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            })

                            Toast.fire({
                                icon: 'error',
                                title: 'Kode Pendaftaran tidak ditemukan.'
                            })
                    }else if(data = "sukses"){
                        // Play success sound
                        let audio = document.getElementById('success-audio');
                        audio.play().then(() => {
                            window.location.href = `{{ route('verifikasi.index') }}`;
                        }).catch((error) => {
                            console.error("Audio play error: ", error);
                            window.location.href = `{{ route('verifikasi.index') }}`;
                        });

                    }else{
                        const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            })
                        Toast.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan.'
                        })
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
                <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
                    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-tab" data-tabs-toggle="#default-tab-content" role="tablist">
                        <li class="me-2" role="presentation">
                            <button class="inline-block p-4 border-b-2 rounded-t-lg" id="profile-tab" data-tabs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Scan QRCode</button>
                        </li>
                        <li class="me-2" role="presentation">
                            <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="dashboard-tab" data-tabs-target="#dashboard" type="button" role="tab" aria-controls="dashboard" aria-selected="false">Verifikasi Manual</button>
                        </li>

                    </ul>
                </div>
                <div id="default-tab-content">
                    <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div id="reader" width="800px"></div>
                        <audio id="success-audio" src="{{ asset('beep_e90.mp3') }}"></audio> <!-- Ganti dengan path file audio Anda -->
                    </div>
                    <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
                            <form action="{{ route('verifikasi.read.manual') }}" method="POST" class="w-full mx-auto space-y-4" enctype="multipart/form-data">
                                @csrf
                                <div>
                                    <x-label-default for="" value="Kode Pendaftaran/ID Transaksi">Kode Pendaftaran/ID Transaksi</x-label-default>
                                    <x-input-default name="kode_pendaftaran" type="text" value="{{ old('kode_pendaftaran') }}" placeholder="Masukkan Kode Pendaftaran/ID Transaksi"></x-input-default>
                                </div>
                                <div class="flex justify-end align-middle content-center bg-slate-100 p-3 rounded-md">
                                    <div>
                                        <x-primary-button type="submit">Simpan</x-primary-button>
                                    </div>
                                    <div>
                                        <x-danger-button type="reset">Batal</x-danger-button>
                                    </div>
                                </div>
                            </form>
                    </div>

                </div>

            </div>
        </section>
    </div>
</x-app-layout>
