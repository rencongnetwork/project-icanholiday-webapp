<div class="card">
    <div class="card-body">
        <table id="dt-users" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No. </th>
                    <th>Nama</th>
                    <th>No. HP</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<script>
    var table, action;

    $(document).ready(function() {
        
        tabel = $('#dt-users').DataTable({
            "processing": true,
            "serverSide": true,
            "ordering": true,
            "bInfo": true,
            "order": [
                [0, 'asc']
            ],
            "ajax": {
                "url": "<?= base_url() . 'users/all' ?>",
                "type": "POST"
            },
            "deferRender": true,
            "aLengthMenu": [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],
            "columns": [{
                    "data": null,
                    "className": "text-center",
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    "data": "fullname"
                },
                {
                    "data": "phone"
                },
                {
                    "data": "email"
                },
                {
                    "data": "status",
                    "className": "text-center",
                    render: function(data, type, row, meta) {
                     if (type === "display") {
                         var html = '';
                         if (data == 1) {
                             html += '<span class="badge badge-pill badge-success">Aktif</span>';
                         } else if (data == 0) {
                             html += '<span class="badge badge-pill badge-secondary">Tidak Aktif</span>';
                         } 
                         return html;
                     } else {
                         return data;
                     }
                    }
                },
                {
                    "data": null,
                    "className": "nowrap, text-center",
                    "render": function(data, type, row, meta) {
                        var html = '';
                        html += '<button class="btn btn-sm btn-outline-primary mr-2 view" title="Lihat">\n\
                                        <i class="fa fa-eye mr-2"></i>Lihat\n\
                                     </button>';
                        html += '<button class="btn btn-sm btn-outline-info mr-2 edit" title="Ubah">\n\
                                        <i class="fa fa-cog mr-2"></i>Ubah\n\
                                     </button>';
                        html += '<button class="btn btn-sm btn-outline-danger delete" title="Hapus">\n\
                                        <i class="fa fa-trash mr-2"></i>Hapus\n\
                                     </button>';
                        return html
                    }
                },
            ],
            columnDefs: [
                { "orderable": false, "targets": 2 },
                { "orderable": false, "targets": -1 }
            ],
        });

    });


</script>