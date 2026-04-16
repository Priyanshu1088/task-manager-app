@extends(session()->has('user_id') && session('user_role') === 'manager' ? 'manager.layout' : 'employees.guest')
@section('content')

<div class="container mt-4">
    <div class="row justify-content-center">
         <div class="col-md-6">
            <div class="card p-4" >
                <div class="card-header text-center fw-bold fs-4">
                    @if(isset($employee))
                        Edit Employee    
                    @elseif(session('user_role') === 'manager')
                        Create Employee Account
                    @else
                        Create Account
                    @endif
                </div>

                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ isset($employee)
                            ? route('manager.employees.update', $employee->id)
                            : route('register.post') }}"
                            method="POST">
                        @csrf
 
                        @if(isset($employee))
                            @method('PUT')
                        @endif

                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text"
                                name="name"
                                class="form-control"
                                value="{{ isset($employee) ? $employee->name : old('name') }}"
                                placeholder="Enter your name">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email"
                                name="email"
                                class="form-control"
                                value="{{ isset($employee) ? $employee->email : old('email') }}"
                                placeholder="Enter your email">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        @if(!isset($employee))
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password"
                                    name="password"
                                    class="form-control"
                                    placeholder="Enter password">
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password"
                                    name="password_confirmation"
                                    class="form-control"
                                    placeholder="Confirm password">
                            </div>

                        @endif

                        <div class="mb-3">
                            <label class="form-label">Mobile Number</label>
                            <input type="text" name="mobile" class="form-control"
                                value="{{ old('mobile') }}" placeholder="Enter mobile number">
                            @error('mobile')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Secondary Mobile (Optional)</label>
                            <input type="text" name="secondary_mobile" class="form-control"
                                value="{{ old('secondary_mobile') }}" placeholder="Enter secondary number">
                            @error('secondary_mobile')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="dob" class="form-control"
                                value="{{ old('dob') }}" max="{{ date('d-m-y') }}">
                            @error('dob')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        @if(session('user_role') !== 'manager')
                            <div class="mb-3"> 
                                <label class="form-label">Register As</label>
                                <select name="role" class="form-control">
                                    <option value="">Select Role</option>
                                    <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>
                                        Manager
                                    </option>
                                    <option value="employee" {{ old('role') == 'employee' ? 'selected' : '' }}>
                                        Employee
                                    </option>
                                </select>
                                @error('role')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @else
                            <input type="hidden" name="role" value="employee">
                        @endif

                        <button type="submit" class="btn btn-primary w-100 mt-3">
                            {{ isset($employee) ? 'Update Employee' : 'Create' }}
                        </button>

                        @if(session('user_role') !== 'manager')
                            <div class="text-center mt-3">
                                <span>Already have an account?</span><br>
                                <a href="{{ route('login') }}">Go to Login</a>
                            </div>
                        @endif

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection