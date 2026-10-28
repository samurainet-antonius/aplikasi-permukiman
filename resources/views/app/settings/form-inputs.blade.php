@php $editing = isset($data) @endphp
@csrf
<div class="flex flex-wrap mb-5">
    
    <div class="form-group">
        <label>Nama Website</label>
        <input
        type="text"
        class="form-control"
        name="site_name"
        value="{{ old('site_name', ($editing ? $data->site_name : '')) }}"
        />
    </div>

    <div class="form-group">
        <label>Deskripsi</label>
        <textarea
        type="text"
        class="form-control"
        name="site_description">{{ old('site_description', ($editing ? $data->site_description : '')) }}</textarea>
    </div>

    <div class="form-group">
        <label>Email</label>
        <input
        type="text"
        class="form-control"
        name="site_email"
        value="{{ old('site_email', ($editing ? $data->site_email : '')) }}"
        />
    </div>

    <div class="form-group">
        <label>Fax Email</label>
        <input
        type="text"
        class="form-control"
        name="site_fax_email"
        value="{{ old('site_fax_email', ($editing ? $data->site_fax_email : '')) }}"
        />
    </div>

    <div class="form-group">
        <label>No. Telpon</label>
        <input
        type="text"
        class="form-control"
        name="site_phone"
        value="{{ old('site_phone', ($editing ? $data->site_phone : '')) }}"
        />
    </div>

    <div class="form-group">
        <label>Alamat</label>
        <textarea
        type="text"
        class="form-control"
        name="site_address">{{ old('site_address', ($editing ? $data->site_address : '')) }}</textarea>
    </div>

</div>