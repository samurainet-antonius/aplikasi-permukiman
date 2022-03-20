@php $editing = isset($user) @endphp
@csrf
<div class="flex flex-wrap mb-5">

    <div class="form-group">
        <label>Name</label>
        <input
        type="text"
        class="form-control"
        name="name"
        value="{{ old('name', ($editing ? $user->name : '')) }}"
        maxlength="255"
        placeholder="Name"
        /> 
    </div>

    <div class="form-group">
        <label>Slug</label>
        <input
        type="text"
        class="form-control"
        name="slug"
        value="{{ old('slug', ($editing ? $user->slug : '')) }}"
        maxlength="255"
        placeholder="Slug"
        /> 
    </div>

    <div class="form-group">
        <label>Email</label>
        <input
        type="email"
        class="form-control"
        name="email"
        value="{{ old('email', ($editing ? $user->email : '')) }}"
        maxlength="255"
        placeholder="Email"
        /> 
    </div>

    <div class="form-group">
        <label>Password</label>
        <input
        type="password"
        class="form-control"
        name="password"
        placeholder="Password"
        :required="!$editing"
        placeholder="Password"
        /> 
    </div>

    <div class="">
        <h4 class="font-bold text-lg text-gray-700">
            Assign @lang('crud.roles.name')
        </h4>

        <div class="py-2">
            @foreach ($roles as $role)
            <div>
                <input
                    type="checkbox"
                    id="role{{ $role->id }}"
                    name="roles[]"
                    value="{{ $role->id }}"
                    @if(isset($user))
                        @if($user->hasRole($role))
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