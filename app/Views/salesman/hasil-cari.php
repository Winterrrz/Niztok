<div class="card" id="data-salesman">
    <div class="card-body">
        <h3 class="card-title">Salesman - <?= $filter_cabang ?><?= $filter_keyword ? ', Keyword: ' . $filter_keyword : '' ?></h3>
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr class="text-center">
                    <th>No</th>
                    <th style="min-width: 140px;">Nama</th>
                    <th style="min-width: 140px;">Cabang</th>
                    <th style="min-width: 140px;">Nomor Telepon</th>
                    <th>Penjualan Bulan Ini</th>
                    <?php if (in_groups('pusat')) : ?>
                        <th style="min-width: 160px;">Aksi</th>
                    <?php endif; ?>
                </tr>
                <?php $nomor = 1 + ($perPage * ($currentPage - 1)); ?>
                <?php if (!empty($data_salesman)) : ?>
                    <?php foreach ($data_salesman as $data) : ?>
                        <tr>
                            <td class="text-center"><?= $nomor++ ?></td>
                            <td class="text-center"><?= $data['nama'] ?></td>
                            <td class="text-center"><?= $data['nama_cabang'] ?></td>
                            <td class="text-center"><?= $data['no_telp'] ?></td>
                            <td class="text-center"><?= $data['total_penjualan'] ?></td>
                            <?php if (in_groups('pusat')) : ?>
                                <td class="text-center">
                                    <a href="<?= base_url('salesman/edit/' . $data['slug']) ?>" class="badge bg-warning">Edit</a>
                                    <a href="/delete" class="badge bg-danger btn-hapus" data-bs-toggle="modal" data-bs-target="#modal-danger" data-id="<?= $data['id'] ?>" data-name="<?= $data['nama'] ?>">
                                        Hapus
                                    </a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr class="text-center">
                        <td colspan="6">Data Salesman Tidak Ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>
    <div class="card-footer d-flex align-items-center justify-content-between">
        <p class="m-0 text-muted">Showing <span><?= ($perPage * ($currentPage - 1)) + 1 ?></span> to <span><?= min($perPage * $currentPage, $total) ?></span> of <span><?= $total ?></span> entries</p>
        <?= $pager; ?>
    </div>
</div>