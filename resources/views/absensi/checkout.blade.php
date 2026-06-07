@extends('layouts.app')

@section('title', 'Check Out')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-warning text-white text-center">
                    <h5 class="mb-0"><i class="bi bi-box-arrow-right"></i> Form Check Out</h5>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Anda akan melakukan check-out. Pastikan mengambil foto dan lokasi Anda saat ini.
                    </div>

                    <form method="POST" action="{{ route('absensi.checkOut') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Kamera -->
                        <div class="mb-3">
                            <label class="form-label"><i class="bi bi-camera"></i> Foto Selfie Pulang</label>
                            <div id="camera-preview">
                                <video id="video" width="100%" autoplay></video>
                                <canvas id="canvas" style="display:none;"></canvas>
                                <button type="button" id="capture-btn" class="btn btn-secondary mt-2">Ambil Foto</button>
                            </div>
                            <input type="file" name="foto_out" id="foto_input" accept="image/*" class="form-control mt-2" style="display:none;">
                            <div id="foto_preview" class="mt-2"></div>
                            @error('foto_out') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>

                        <!-- Lokasi -->
                        <div class="mb-3">
                            <label class="form-label"><i class="bi bi-geo-alt"></i> Lokasi Check-Out</label>
                            <div id="lokasi_status" class="alert alert-info">Mengambil lokasi...</div>
                            <input type="hidden" name="latitude_out" id="latitude">
                            <input type="hidden" name="longitude_out" id="longitude">
                            <input type="hidden" name="nama_lokasi_out" id="nama_lokasi">
                        </div>

                        <!-- Keterangan -->
                        <div class="mb-3">
                            <label class="form-label"><i class="bi bi-chat-text"></i> Keterangan (opsional)</label>
                            <textarea name="keterangan_out" class="form-control" rows="3" placeholder="Misal: keterlambatan pulang, dll."></textarea>
                        </div>

                        <button type="submit" class="btn btn-warning btn-lg w-100">
                            <i class="bi bi-check-circle"></i> Simpan Check-Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // ========== KAMERA ==========
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const captureBtn = document.getElementById('capture-btn');
    const fotoInput = document.getElementById('foto_input');
    const fotoPreview = document.getElementById('foto_preview');
    let stream = null;

    async function startCamera() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ video: true });
            video.srcObject = stream;
        } catch (err) {
            alert("Tidak dapat mengakses kamera. Gunakan upload file biasa.");
            fotoInput.style.display = 'block';
            captureBtn.style.display = 'none';
        }
    }

    captureBtn.addEventListener('click', function() {
        const context = canvas.getContext('2d');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        const dataURL = canvas.toDataURL('image/jpeg');
        const blob = dataURLtoBlob(dataURL);
        const file = new File([blob], 'selfie_out.jpg', { type: 'image/jpeg' });
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        fotoInput.files = dataTransfer.files;

        fotoPreview.innerHTML = `<img src="${dataURL}" width="150" class="img-thumbnail">`;
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            video.style.display = 'none';
            captureBtn.disabled = true;
        }
    });

    function dataURLtoBlob(dataURL) {
        const arr = dataURL.split(',');
        const mime = arr[0].match(/:(.*?);/)[1];
        const bstr = atob(arr[1]);
        let n = bstr.length;
        const u8arr = new Uint8Array(n);
        while (n--) { u8arr[n] = bstr.charCodeAt(n); }
        return new Blob([u8arr], { type: mime });
    }

    startCamera();

    // ========== LOKASI ==========
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, showError);
        } else {
            document.getElementById('lokasi_status').innerHTML = "Geolocation tidak didukung.";
        }
    }

    function showPosition(position) {
        const lat = position.coords.latitude;
        const lng = position.coords.longitude;
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;

        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
            .then(response => response.json())
            .then(data => {
                const nama = data.display_name || `${lat}, ${lng}`;
                document.getElementById('nama_lokasi').value = nama;
                document.getElementById('lokasi_status').innerHTML = `<i class="bi bi-check-circle"></i> Lokasi: ${nama}`;
            })
            .catch(() => {
                document.getElementById('nama_lokasi').value = `${lat}, ${lng}`;
                document.getElementById('lokasi_status').innerHTML = `<i class="bi bi-check-circle"></i> Lokasi: ${lat}, ${lng}`;
            });
    }

    function showError(error) {
        let msg = '';
        switch(error.code) {
            case error.PERMISSION_DENIED: msg = "Izin lokasi ditolak."; break;
            case error.POSITION_UNAVAILABLE: msg = "Lokasi tidak tersedia."; break;
            case error.TIMEOUT: msg = "Timeout mengambil lokasi."; break;
        }
        document.getElementById('lokasi_status').innerHTML = `<div class="alert alert-danger">${msg}</div>`;
    }

    getLocation();
</script>
@endpush
@endsection