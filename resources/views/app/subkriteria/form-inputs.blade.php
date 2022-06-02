@php $editing = isset($subkriteria) @endphp
@csrf
<div class="flex flex-wrap mb-5">

    <div class="form-group">
        <label>Kriteria</label>
        <select class="select2-single form-control" name="kriteria_id" id="select2Single">
            @foreach ($kriteria as $item)
            <option value="{{$item->id}}" {{ ($editing ? (($subkriteria->kriteria_id == $item->id) ? 'selected' : '') : '') }}>{{$item->nama}}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Nama Subkriteria</label>
        <textarea
        class="form-control"
        name="nama"
        >{{ old('nama', ($editing ? $subkriteria->nama : '')) }}</textarea>
    </div>

     <div class="form-group">
        <label>Pilihan Jawaban</label>

        <div id="dynamicAddRemove">
            <div class="row mb-3">
                <div class="col">
                    <input type="text" class="form-control" name="jawaban[]" value="{{ old('name', ($editing ? $subkriteria->pilihan[0]->jawaban : '')) }}" placeholder="Jawaban">
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="skor[]" value="{{ old('name', ($editing ? $subkriteria->pilihan[0]->skor : '')) }}" placeholder="Skor">
                </div>
                <div class="col">
                    <button type="button" name="add" id="add-btn" class="btn btn-success"><i class="mr-1 fa fa-solid fa-plus"></i></button>
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
                    '<div class="col">'+
                        '<input type="text" class="form-control" name="jawaban[]" placeholder="Jawaban">'+
                    '</div>'+
                    '<div class="col">'+
                        '<input type="text" class="form-control" name="skor[]" placeholder="Skor">'+
                    '</div>'+
                    '<div class="col">'+
                        '<button type="button" class="btn btn-danger remove-tr"><i class="fa fa-solid fa-trash"></i></button></td>'+
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
        var pilihan = <?php echo json_encode($subkriteria->pilihan); ?>;
        pilihan.shift();
        pilihan.forEach(val => {
            $("#dynamicAddRemove").append(
                '<div class="row mb-3" id="dynamic">'+
                    '<div class="col">'+
                        '<input type="text" class="form-control" value="'+val.jawaban+'" name="jawaban[]" placeholder="Jawaban">'+
                    '</div>'+
                    '<div class="col">'+
                        '<input type="text" class="form-control" value="'+val.skor+'" name="skor[]" placeholder="Skor">'+
                    '</div>'+
                    '<div class="col">'+
                        '<button type="button" class="btn btn-danger remove-tr"><i class="fa fa-solid fa-trash"></i></button></td>'+
                    '</div>'+
                '</div>'
            );
        });
    </script>
    @endif

</div>
