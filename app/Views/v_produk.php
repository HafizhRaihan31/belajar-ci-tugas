<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<!-- Flash Messages -->
<?php if (session()->getFlashData('success')) : ?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <?= esc(session()->getFlashData('success')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?php if (session()->getFlashData('failed')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= esc(session()->getFlashData('failed')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Tombol Tambah -->
<button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#addModal">
    Tambah Data
</button>

<a type="button" class="btn btn-success mb-2" href="<?= base_url() ?> produk/download">
    download data
</a>

<!-- Table Produk -->
<table id="ProdukTable" class="table datatable">
    <thead>
        <tr>
            <th>No.</th>
            <th>Nama</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Foto</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($product as $index => $produk) : ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?= esc($produk['nama']) ?></td>
                <td><?= esc($produk['harga']) ?></td>
                <td><?= esc($produk['jumlah']) ?></td>
                <td>
                    <?php if (!empty($produk['foto']) && file_exists(FCPATH . 'img/' . $produk['foto'])) : ?>
                        <img src="<?= base_url("img/" . $produk['foto']) ?>" width="100px">
                    <?php else : ?>
                        <span>-</span>
                    <?php endif; ?>
                </td>
                <td>
                    <button 
                        type="button" 
                        class="btn btn-success btn-edit"
                        data-id="<?= $produk['id'] ?>"
                        data-nama="<?= esc($produk['nama']) ?>"
                        data-harga="<?= esc($produk['harga']) ?>"
                        data-jumlah="<?= esc($produk['jumlah']) ?>"
                        data-foto="<?= $produk['foto'] ?>"
                        data-bs-toggle="modal" 
                        data-bs-target="#editModal">
                        Ubah
                    </button>
                    <a href="<?= base_url('produk/delete/' . $produk['id']) ?>" class="btn btn-danger" onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Modal Tambah -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="<?= base_url('produk') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-2">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="form-group mb-2">
                        <label>Harga</label>
                        <input type="number" name="harga" class="form-control" required>
                    </div>
                    <div class="form-group mb-2">
                        <label>Jumlah</label>
                        <input type="number" name="jumlah" class="form-control" required>
                    </div>
                    <div class="form-group mb-2">
                        <label>Foto</label>
                        <input type="file" name="foto" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit (Dinamis) -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="editForm" method="post" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id">
                    <div class="form-group mb-2">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" id="edit-nama" required>
                    </div>
                    <div class="form-group mb-2">
                        <label>Harga</label>
                        <input type="number" name="harga" class="form-control" id="edit-harga" required>
                    </div>
                    <div class="form-group mb-2">
                        <label>Jumlah</label>
                        <input type="number" name="jumlah" class="form-control" id="edit-jumlah" required>
                    </div>
                    <div class="form-group mb-2">
                        <img id="edit-preview" src="" width="100px" class="mb-2" style="display:none;">
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="edit-check" name="check" value="1">
                        <label class="form-check-label" for="edit-check">Ceklis jika ingin mengganti foto</label>
                    </div>
                    <div class="form-group">
                        <label>Foto</label>
                        <input type="file" class="form-control" name="foto">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Inisialisasi & Edit Modal Logic -->
<script>
    $(document).ready(function () {
        $('#ProdukTable').DataTable({
            pageLength: 10,
            language: {
                lengthMenu: "_MENU_ data per halaman",
                search: "",
                searchPlaceholder: "Cari..."
            }
        });

        // Saat tombol edit ditekan
        $('.btn-edit').on('click', function () {
            const id = $(this).data('id');
            const nama = $(this).data('nama');
            const harga = $(this).data('harga');
            const jumlah = $(this).data('jumlah');
            const foto = $(this).data('foto');

            $('#editForm').attr('action', '<?= base_url('produk/edit/') ?>' + id);
            $('#edit-id').val(id);
            $('#edit-nama').val(nama);
            $('#edit-harga').val(harga);
            $('#edit-jumlah').val(jumlah);

            if (foto) {
                $('#edit-preview').attr('src', '<?= base_url('img/') ?>' + foto).show();
            } else {
                $('#edit-preview').hide();
            }

            $('#edit-check').prop('checked', false);
        });
    });
</script>

<?= $this->endSection() ?>
