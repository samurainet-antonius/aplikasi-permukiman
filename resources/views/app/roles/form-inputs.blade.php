@php $editing = isset($role) @endphp
@csrf
<div class="flex flex-wrap mb-5">

    <div class="form-group">
        <label>Name</label>
        <input
        type="text"
        class="form-control"
        name="name"
        value="{{ old('name', ($editing ? $role->name : '')) }}"
        maxlength="255"
        placeholder="Name"
        /> 
    </div>

</div>