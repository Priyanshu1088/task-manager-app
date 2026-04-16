
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {
        background: linear-gradient(140deg, #000, #764ba2);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .error {
        color: red;
        font-size: 14px;
        margin-top: 5px;
        display: block;
    }
        

</style>
</head>
<body>

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

    <div class="login-form">
        <div class="card shadow p-4" style="width: 500px;">
                    <div class="card">
                        <div class="card-header">Login</div>
                        <div class="card-body">

                            <form action="{{route('login.post')}}" method="post">
                                @csrf
                                <div class="row mb-3 align-items-center">
                                    <label for="email_address"
                                    class="col-md-4 col-form-label text-md-right">
                                   
                                        E-mail Address
                                    </label>
                                    <div class="col-md-7">
                                        <input type="text" id="email_address" class="form-control" name="email"  autofocus>
                                         <span class="error">@error('email') {{ $message }} @enderror</span>
                                    </div>
                                </div>

                                <div class="row mb-3 align-items-center">

                                    <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                                    <div class="col-md-7">

                                        <input type="password" id="password" class="form-control" name="password" >
                                        <span class="error">@error('password') {{ $message }} @enderror</span>
                                    </div>
                                </div>

                                

                                <div class="col-md-7 offset-md-5  mt-3">

                                    <button type="submit" class="btn btn-primary">

                                        Login

                                    </button>

                                </div>

                                <div class="text-center mt-3">
                                    <span class="text-dark">don't have an account?</span><br>
                                    
                                    <a href="{{ route('register') }}" class="btn btn-outline-dark w-100 mt-2">Go to register page</a>
                                </div>
                            </form>
                            
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
</body>
</html>