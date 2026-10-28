@php

$editing = isset($kriteria);

$id = '';
$nama = '';
$satuan = '';
if($editing) {
    if(count($kriteria->subkriteria) > 0){
        $id = $kriteria->subkriteria[0]->id;
        $nama = $kriteria->subkriteria[0]->nama;
        $satuan = $kriteria->subkriteria[0]->satuan;
    }
}

@endphp
@csrf
<div class="flex flex-wrap mb-5">

    <div class="form-group">
        <label>Nama Kriteria</label>
        <textarea class="form-control" name="nama">{{ old('name', ($editing ? $kriteria->nama : '')) }}</textarea>
    </div>

    <div class="form-group">

        <div id="dynamicAddRemove">
            <div class="row mb-3">
                <div class="col-7">
                    <label>Nama Subkriteria</label>
                    <input type="hidden" name="subkriteria_id[]" value="{{ old('name', $id) }}">
                    <textarea class="form-control" name="subkriteria[]">{{ old('name', $nama) }}</textarea>
                </div>
                <div class="col-3">
                    <label>Satuan</label>
                    <input type="text" class="form-control" name="satuan[]" value="{{ old('name', $satuan) }}" placeholder="Satuan">
                </div>
                <div class="col-2 mt-4">
                    <button type="button" name="add" id="add-btn" class="btn btn-success mt-2"><i class="mr-1 fa fa-solid fa-plus"></i></button>
                </div>
            </div>
        </div>
    </div>

</div>

    <script type="text/javascript">
        var i = 0;
        $("#add-btn").click(function () {
            ++i;
            $("#dynamicAddRemove").append(
                '<div class="row mb-3" id="dynamic">'+
                    '<div class="col-7">'+
                        '<label>Nama Subkriteria</label>'+
                        '<input type="hidden" name="subkriteria_id[]" value="">'+
                        '<textarea class="form-control" name="subkriteria[]"></textarea>'+
                    '</div>'+
                    '<div class="col-3">'+
                        '<label>Satuan</label>'+
                        '<input type="text" class="form-control" name="satuan[]" placeholder="Satuan">'+
                    '</div>'+
                    '<div class="col-2 mt-4">'+
                        '<button type="button" class="btn btn-danger mt-2 remove-tr"><i class="fa fa-solid fa-trash"></i></button></td>'+
                    '</div>'+
                '</div>'
            );
        });

        $(document).on('click', '.remove-tr', function () {
            $(this).parents('#dynamic').remove();
        });

    </script>

    @if ($editing)
    <script type="text/javascript">
        var subkriteria = <?php echo json_encode($kriteria->subkriteria); ?>;
        console.log(subkriteria);
        subkriteria.shift();
        subkriteria.forEach(val => {
            $("#dynamicAddRemove").append(
                '<div class="row mb-3" id="dynamic">'+
                    '<div class="col-7">'+
                        '<label>Nama Subkriteria</label>'+
                        '<input type="hidden" name="subkriteria_id[]" value="'+val.id+'">'+
                        '<textarea class="form-control" name="subkriteria[]">'+val.nama+'</textarea>'+
                    '</div>'+
                    '<div class="col-3">'+
                        '<label>Satuan</label>'+
                        '<input type="text" class="form-control" value="'+val.satuan+'" name="satuan[]" placeholder="Satuan">'+
                    '</div>'+
                    '<div class="col-2 mt-4">'+
                        '<button type="button" class="btn btn-danger mt-2 remove-tr"><i class="fa fa-solid fa-trash"></i></button></td>'+
                    '</div>'+
                '</div>'
            );
        });
    </script>
    @endif
