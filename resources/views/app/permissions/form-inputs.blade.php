@php $editing = isset($permission) @endphp
@csrf
<div class="flex flex-wrap mb-5">

    <div class="form-group">
        <label>Name</label>
        <input
        type="text"
        class="form-control"
        name="name"
        value="{{ old('name', ($editing ? $permission->name : '')) }}"
        maxlength="255"
        placeholder="Name"
        />
    </div>

    <div class="">
        <h4 class="font-bold text-lg text-gray-700">
            Assign @lang('crud.permissions.name')
        </h4>

        <div class="py-2">
            @foreach ($roles as $role)
            <div>
                <input
                    type="checkbox"
                    id="role{{ $role->id }}"
                    name="roles[]"
                    value="{{ $role->id }}"
                    @if(isset($permission))
                        @if($permission->hasRole($role))
                            checked
                        @endif
                    @endif
                />
                {{ ucfirst($role->name) }}
            </div>
            @endforeach
        </div>
    </div>

</div>
