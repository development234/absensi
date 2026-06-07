@extends('layouts.app')

@section('title', 'Absensi Hari Ini')

@section('content')
<div class="container">
    <div class="row g-4">
        <!-- KOLOM KIRI: CHECK-IN -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-header bg-gradient-primary text-white border-0 rounded-top-4">
                    <h5 class="mb-0"><i class="bi bi-check-circle-fill me-2"></i> Check-In</h5>
                </div>
                <div class="card-body p-4">
                    @if($today && $today->jam_masuk)
                        <!-- Tampilan setelah check-in (info) -->
                        <div class="text-center mb-3">
                            <div class="position-relative d-inline-block">
                                @if($today->foto)
                                    <img src="{{ Storage::url($today->foto) }}" 
                                         class="rounded-circle border border-3 border-white shadow-sm" 
                                         width="120" height="120" style="object-fit: cover;">
                                @else
                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                                         style="width: 120px; height: 120px;">
                                        <i class="bi bi-person-circle fs-1 text-white"></i>
                                    </div>
                                @endif
                            </div>
                            <h5 class="fw-bold mt-3 text-primary">Halo, {{ auth()->user()->name }}</h5>
                            <span class="badge bg-success px-3 py-2 rounded-pill">
                                {{ \Carbon\Carbon::parse($today->tanggal)->translatedFormat('l, d F Y') }}
                            </span>
                        </div>

                        <div class="vstack gap-3">
                            <div class="d-flex align-items-center gap-3 p-2 bg-light rounded-3">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-2">
                                    <i class="bi bi-clock-history fs-5 text-primary"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Jam Masuk</small>
                                    <strong class="fs-5">{{ $today->jam_masuk }}</strong>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3 p-2 bg-light rounded-3">
                                <div class="bg-success bg-opacity-10 rounded-circle p-2">
                                    <i class="bi bi-check-circle fs-5 text-success"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Status</small>
                                    <strong class="fs-5 text-capitalize">{{ $today->status }}</strong>
                                </div>
                            </div>
                            <div class="d-flex align-items-start gap-3 p-2 bg-light rounded-3">
                                <div class="bg-info bg-opacity-10 rounded-circle p-2 mt-1">
                                    <i class="bi bi-geo-alt-fill fs-5 text-info"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Lokasi</small>
                                    <strong>{{ $today->nama_lokasi }}</strong>
                                    @if($today->latitude && $today->longitude)
                                        <br><small class="text-muted">({{ number_format($today->latitude, 6) }}, {{ number_format($today->longitude, 6) }})</small>
                                    @endif
                                </div>
                            </div>
                            @if($today->tugas)
                            <div class="d-flex align-items-start gap-3 p-2 bg-light rounded-3">
                                <div class="bg-warning bg-opacity-10 rounded-circle p-2">
                                    <i class="bi bi-clipboard-list fs-5 text-warning"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Tugas</small>
                                    <p class="mb-0">{{ $today->tugas }}</p>
                                </div>
                            </div>
                            @endif
                            @if($today->keterangan)
                            <div class="d-flex align-items-start gap-3 p-2 bg-light rounded-3">
                                <div class="bg-secondary bg-opacity-10 rounded-circle p-2">
                                    <i class="bi bi-chat-left-text fs-5 text-secondary"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Keterangan</small>
                                    <p class="mb-0">{{ $today->keterangan }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    @else
                        <!-- Form Check-In -->
                        <form method="POST" action="{{ route('absensi.checkIn') }}" enctype="multipart/form-data" id="formCheckIn">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold"><i class="bi bi-camera"></i> Foto Selfie</label>
                                <div id="camera-preview-in">
                                    <video id="video-in" width="100%" autoplay></video>
                                    <canvas id="canvas-in" style="display:none;"></canvas>
                                    <button type="button" id="capture-btn-in" class="btn btn-secondary mt-2"><i class="bi bi-camera"></i>Ambil Foto</button>
                                </div>
                                <input type="file" name="foto" id="foto-input-in" accept="image/*" style="display:none;">
                                <div id="foto-preview-in" class="mt-2 text-center"></div>
                                @error('foto') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold"><i class="bi bi-geo-alt"></i> Lokasi</label>
                                <div id="lokasi-status-in" class="alert alert-info py-2">Mengambil lokasi...</div>
                                <input type="hidden" name="latitude" id="latitude-in">
                                <input type="hidden" name="longitude" id="longitude-in">
                                <input type="hidden" name="nama_lokasi" id="nama-lokasi-in">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold"><i class="bi bi-clipboard"></i> Tugas (opsional)</label>
                                <textarea name="tugas" class="form-control" rows="2" placeholder="Deskripsi tugas hari ini..."></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold"><i class="bi bi-flag"></i> Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="hadir">Hadir</option>
                                    <option value="izin">Izin</option>
                                    <option value="sakit">Sakit</option>
                                    <option value="alpha">Alpha</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold"><i class="bi bi-chat"></i> Keterangan (opsional)</label>
                                <textarea name="keterangan" class="form-control" rows="2" placeholder="Misal: keterlambatan..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-success w-100 rounded-pill py-2 bg-gradient-primary">
                                <i class="bi bi-check-circle"></i> Check In
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: CHECK-OUT -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-header bg-gradient-warning text-white border-0 rounded-top-4">
                    <h5 class="mb-0"><i class="bi bi-box-arrow-right me-2"></i> Check-Out</h5>
                </div>
                <div class="card-body p-4">
                    @if($today && $today->jam_keluar)
                        <!-- Tampilan setelah check-out (info) -->
                        <div class="text-center mb-3">
                            <div class="position-relative d-inline-block">
                                @if($today->foto_out)
                                    <img src="{{ Storage::url($today->foto_out) }}" 
                                         class="rounded-circle border border-3 border-white shadow-sm" 
                                         width="120" height="120" style="object-fit: cover;">
                                @else
                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                                         style="width: 120px; height: 120px;">
                                        <i class="bi bi-person-circle fs-1 text-white"></i>
                                    </div>
                                @endif
                            </div>
                            <span class="badge bg-warning mt-2 px-3 py-2 rounded-pill">
                                <i class="bi bi-check-circle-fill me-1"></i> Selesai
                            </span>
                        </div>
                        <div class="vstack gap-3">
                            <div class="d-flex align-items-center gap-3 p-2 bg-light rounded-3">
                                <div class="bg-warning bg-opacity-10 rounded-circle p-2">
                                    <i class="bi bi-clock-history fs-5 text-warning"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Jam Keluar</small>
                                    <strong class="fs-5">{{ $today->jam_keluar }}</strong>
                                </div>
                            </div>
                            <div class="d-flex align-items-start gap-3 p-2 bg-light rounded-3">
                                <div class="bg-info bg-opacity-10 rounded-circle p-2 mt-1">
                                    <i class="bi bi-geo-alt-fill fs-5 text-info"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Lokasi Pulang</small>
                                    <strong>{{ $today->nama_lokasi_out }}</strong>
                                    @if($today->latitude_out && $today->longitude_out)
                                        <br><small class="text-muted">({{ number_format($today->latitude_out, 6) }}, {{ number_format($today->longitude_out, 6) }})</small>
                                    @endif
                                </div>
                            </div>
                            @if($today->keterangan_out)
                            <div class="d-flex align-items-start gap-3 p-2 bg-light rounded-3">
                                <div class="bg-secondary bg-opacity-10 rounded-circle p-2">
                                    <i class="bi bi-chat-left-quote fs-5 text-secondary"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Keterangan Pulang</small>
                                    <p class="mb-0">{{ $today->keterangan_out }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    @elseif($today && $today->jam_masuk && !$today->jam_keluar)
                        <!-- Form Check-Out (sudah check-in, belum check-out) -->
                        <form method="POST" action="{{ route('absensi.checkOut') }}" enctype="multipart/form-data" id="formCheckOut">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold"><i class="bi bi-camera"></i> Foto Selfie Pulang</label>
                                <div id="camera-preview-out">
                                    <video id="video-out" width="100%" autoplay></video>
                                    <canvas id="canvas-out" style="display:none;"></canvas>
                                    <button type="button" id="capture-btn-out" class="btn btn-secondary mt-2">Ambil Foto</button>
                                </div>
                                <input type="file" name="foto_out" id="foto-input-out" accept="image/*" style="display:none;">
                                <div id="foto-preview-out" class="mt-2 text-center"></div>
                                @error('foto_out') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold"><i class="bi bi-geo-alt"></i> Lokasi Pulang</label>
                                <div id="lokasi-status-out" class="alert alert-info py-2">Mengambil lokasi...</div>
                                <input type="hidden" name="latitude_out" id="latitude-out">
                                <input type="hidden" name="longitude_out" id="longitude-out">
                                <input type="hidden" name="nama_lokasi_out" id="nama-lokasi-out">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold"><i class="bi bi-chat"></i> Keterangan Pulang (opsional)</label>
                                <textarea name="keterangan_out" class="form-control" rows="2" placeholder="Misal: lembur, dll."></textarea>
                            </div>
                            <button type="submit" class="btn btn-warning w-100 rounded-pill py-2 text-white fw-bold bg-gradient-warning">
                                <i class="bi bi-box-arrow-right"></i> Check Out
                            </button>
                        </form>
                    @else
                        <!-- Kondisi belum check-in sama sekali -->
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-info-circle fs-1"></i>
                            <p class="mt-3">Silakan melakukan check-in terlebih dahulu di kolom sebelah kiri.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Fungsi umum untuk kamera dan lokasi
    function initCameraAndLocation(side) {
        const video = document.getElementById(`video-${side}`);
        const canvas = document.getElementById(`canvas-${side}`);
        const captureBtn = document.getElementById(`capture-btn-${side}`);
        const fotoInput = document.getElementById(`foto-input-${side}`);
        const fotoPreview = document.getElementById(`foto-preview-${side}`);
        let stream = null;

        async function startCamera() {
            try {
                stream = await navigator.mediaDevices.getUserMedia({ video: true });
                video.srcObject = stream;
            } catch (err) {
                fotoInput.style.display = 'block';
                captureBtn.style.display = 'none';
                alert("Kamera tidak tersedia, gunakan upload file.");
            }
        }

        captureBtn.addEventListener('click', () => {
            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const dataURL = canvas.toDataURL('image/jpeg');
            const blob = dataURLtoBlob(dataURL);
            const file = new File([blob], `selfie_${side}.jpg`, { type: 'image/jpeg' });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fotoInput.files = dataTransfer.files;
            fotoPreview.innerHTML = `<img src="${dataURL}" class="rounded-circle shadow-sm" width="100" height="100" style="object-fit: cover;">`;
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                video.style.display = 'none';
                captureBtn.disabled = true;
            }
        });

        startCamera();

        // Lokasi
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(position => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                document.getElementById(`latitude-${side}`).value = lat;
                document.getElementById(`longitude-${side}`).value = lng;
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18`)
                    .then(res => res.json())
                    .then(data => {
                        const nama = data.display_name || `${lat}, ${lng}`;
                        document.getElementById(`nama-lokasi-${side}`).value = nama;
                        document.getElementById(`lokasi-status-${side}`).innerHTML = `<i class="bi bi-check-circle"></i> ${nama.substring(0, 80)}`;
                    })
                    .catch(() => {
                        document.getElementById(`nama-lokasi-${side}`).value = `${lat}, ${lng}`;
                        document.getElementById(`lokasi-status-${side}`).innerHTML = `<i class="bi bi-check-circle"></i> ${lat}, ${lng}`;
                    });
            }, error => {
                let msg = "Lokasi tidak dapat diambil.";
                document.getElementById(`lokasi-status-${side}`).innerHTML = `<div class="alert alert-danger py-1">${msg}</div>`;
            });
        } else {
            document.getElementById(`lokasi-status-${side}`).innerHTML = "Geolocation tidak didukung.";
        }
    }

    function dataURLtoBlob(dataURL) {
        const arr = dataURL.split(',');
        const mime = arr[0].match(/:(.*?);/)[1];
        const bstr = atob(arr[1]);
        let n = bstr.length;
        const u8arr = new Uint8Array(n);
        while (n--) u8arr[n] = bstr.charCodeAt(n);
        return new Blob([u8arr], { type: mime });
    }

    // Inisialisasi untuk form check-in jika belum check-in
    @if(!($today && $today->jam_masuk))
        initCameraAndLocation('in');
    @endif

    // Inisialisasi untuk form check-out jika sudah check-in tapi belum check-out
    @if($today && $today->jam_masuk && !$today->jam_keluar)
        initCameraAndLocation('out');
    @endif
</script>
@endpush