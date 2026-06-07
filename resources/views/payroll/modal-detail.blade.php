<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="detailModalLabel">Detail Penggajian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modalContent">
                    <div class="text-center">Memuat data...</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('detailModal').addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var payrollId = button.getAttribute('data-id');
        
        document.getElementById('modalContent').innerHTML = '<div class="text-center">Memuat data...</div>';
        
        fetch(`/payroll/${payrollId}/detail`)
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    document.getElementById('modalContent').innerHTML = `
                        <table class="table table-bordered">
                            <tr><th width="35%">Nama Karyawan</th><td>${data.user_name}</td></tr>
                            <tr><th>Jabatan</th><td>${data.jabatan}</td></tr>
                            <tr><th>Periode</th><td>${data.nama_bulan} ${data.tahun}</td></tr>
                            <tr><th>Gaji Pokok</th><td>Rp ${formatNumber(data.gaji_pokok)}</td></tr>
                            <tr><th>Uang Makan</th><td>Rp ${formatNumber(data.uang_makan)}</td></tr>
                            <tr><th>Tunjangan</th><td>Rp ${formatNumber(data.tunjangan)}</td></tr>
                            <tr><th>Lembur</th><td>Rp ${formatNumber(data.lembur)}</td></tr>
                            <tr><th>Potongan</th><td>Rp ${formatNumber(data.potongan)}</td></tr>
                            <tr class="table-active"><th>Total Gaji</th><td><strong>Rp ${formatNumber(data.total_gaji)}</strong></td></tr>
                            <tr><th>Keterangan</th><td>${data.keterangan || '-'}</td></tr>
                        </table>
                    `;
                } else {
                    document.getElementById('modalContent').innerHTML = '<div class="alert alert-danger">Gagal memuat data.</div>';
                }
            })
            .catch(error => {
                document.getElementById('modalContent').innerHTML = '<div class="alert alert-danger">Terjadi kesalahan.</div>';
            });
    });
    
    function formatNumber(amount) {
        return new Intl.NumberFormat('id-ID').format(amount);
    }
</script>