@extends('layouts.app')

@section('content')
<div style=" position: absolute; background-color: #102c4c; width: 100vw; height: 50vh; z-index: -1;"></div>
<div class="container" >
    <div class="row justify-content-center">
        <div class="col-md-8" style="display: flex;height:100vh;width:100vw;">
            <div class="card" style="margin: auto; min-width:350px;width:35vw;padding:20px; max-width: 500px; background: rgba(255, 255, 255, 0.4); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);  border-radius: 10px;">
                {{-- <div class="card-header">{{ __('Register') }}</div> --}}
                

                <div class="card-body" style="padding: 40px 0;">
                    <div class="SmallLogo" style=" margin: auto; width: 80px; height: 80px; ">
                        <img src="{{ asset('build/assets/images/RentLab.png') }}" style="width: 90%; height: 90%; display: flex; justify-content: center; align-items: center; margin: auto; ">
                    </div>
                    <p style="padding:3px;text-align: center; font-weight: bold; font-size: 25px; font-family:Verdana, Geneva, Tahoma, sans-serif; margin: 0;">Sign Up Now</p>
                    <p style="padding: 3px; text-align: center; font-size: medium;">Already have a RentLab Account? <a href="{{  route('login') }}" style=" text-decoration: none;">Login</a></p>
                    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" style="display: flex;flex-direction:column;">
                        @csrf
                        <div style="width: 80%;display: flex;margin:auto; padding: 0;">
                            <a href="{{ route('google.login') }}" class="btn btn-danger" style=" padding: 0;;margin: auto;width:100%; height: 45px; text-align: center; font-weight: bold; font-size: large; background-color: transparent; border: 2px solid black; display: flex;">
                                <div style="margin: auto; display: flex;">
                                    <div style="width:30px;height:30px;margin:auto;  border-radius: 20px; display: flex;">
                                        <img src="{{ asset('build/assets/images/GoogleLogo.png') }}" style="width: 100%; height: 100%; display: flex; justify-content: center; align-items: center; margin: auto;">
                                    </div>
                                    <i class="fab fa-google" style="padding: 4px"></i> Google
                                </div>
                            </a> 
                        </div>
                        <br>
                        <div style="display: flex; width: 80%; margin: auto;;">
                            <div style="flex: 5; height: 2px; background-color: black; margin: auto;"></div>
                            <p style="margin:auto; flex: 3; text-align:center; font-size: 15px;">ATAU</p>
                            <div style="flex: 5; height: 2px; background-color: black; margin: auto;"></div>
                        </div>
                        <br>
                        <div class="row mb-3" style="width: 80%;display: flex;margin: auto;">
                            <div class="col-md-6" style="width: 100%;display: flex;border:2px solid black; padding: 3px;border-radius:8px;">
                                <div style="width:30px;height:30px;margin:auto; border-radius: 20px; display: flex;">
                                    <img src="{{ asset('build/assets/images/UsernameLogo.png') }}" style="width: 25px; height: 25px; display: flex; justify-content: center; align-items: center; margin: auto;">
                                </div>
                                <input placeholder="Username" style="background-color: transparent !important; margin: auto; border: none;" id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3" style="width: 80%;display: flex;margin:auto;">
                            <div class="col-md-6" style="width: 100%;display: flex;border:2px solid black; padding: 3px;border-radius:8px;">
                                <div style="width:30px;height:30px;margin:auto; border-radius: 20px; display: flex;">
                                    <img src="{{ asset('build/assets/images/MailLogo.png') }}" style="width: 25px; height: 20px; display: flex; justify-content: center; align-items: center; margin: auto;">
                                </div>
                                <input placeholder="Email" style="margin: auto; background-color: transparent; border: none;" id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div> 
                        
                        <div class="row mb-3" style="width: 80%;display: flex;margin:auto;">
                            <div class="col-md-6" style="width: 100%;display: flex;border:2px solid black; padding: 3px;border-radius:8px;">
                                <div style="width:30px;height:30px;margin:auto; border-radius: 20px; display: flex;">
                                    <img src="{{ asset('build/assets/images/PasswordLogo.png') }}" style="width: 25px; height: 25px; display: flex; justify-content: center; align-items: center; margin: auto;">
                                </div>
                                <input placeholder="Password" style="margin: auto; background-color: transparent; border: none;" id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3" style="width: 80%;display: flex;margin:auto;">
                            <div class="col-md-6" style="width: 100%;display: flex;border:2px solid black; padding: 3px;border-radius:8px;">
                                <div style="width:30px;height:30px;margin:auto; border-radius: 20px; display: flex;">
                                    <img src="{{ asset('build/assets/images/ConfirmPasswordLogo.png') }}" style="width: 25px; height: 25px; display: flex; justify-content: center; align-items: center; margin: auto;">
                                </div>
                                <input placeholder="Confirm Password" style="margin: auto; background-color: transparent; border: none;" id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                
                                
                               
                            </div>
                            <span id="password-confirm" class="invalid-feedback" role="alert" style="display: none; margin: auto; padding: 0;">
                                <strong>Password Doesn't Match</strong>
                            </span>
                        </div>
                        
                        <div style="width: 80%;display: flex;margin:auto; padding: 3px;">
                            <button id="SubmitButton" type="submit" class="btn btn-primary" style="width: 100%; font-weight: bold; font-size: large; background-color: #90b4ec; border: 1px solid #90b4ec;">
                                {{ __('Register') }}
                            </button>
                        </div>
                        <p style="padding-top: 15px; text-align: center; font-size: medium; margin: 0; font-weight: bold;">By registering i agree to</p>
                        <p style=" text-align: center; font-size: medium;"><a href="{{  route('login') }}" style=" text-decoration: none;">Login</a></p>
                        
                        {{-- <div class="row mb-0" style="width: 80%;display: flex;margin:none;">
                            <div class="col-md-6 offset-md-4" style="width: 100%;display: flex;border:1px solid gray; padding: 3px;border-radius:8px;">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>  --}}
                    </form>
                </div>
            </div>
            <div class="BigLogo" style="width: 350px; height: 350px; background-color:  #20447c; margin: auto; border-radius: 250px; display: flex; ">
                <div style="margin: auto; background-color: white; width: 80%; height: 80%; border-radius: 200px; display: flex;">
                    <img src="{{ asset('build/assets/images/RentLab.png') }}" style="width: 80%; height: 80%; display: flex; justify-content: center; align-items: center; margin: auto;">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    //responsive logo
    let width=window.innerWidth;
    window.addEventListener('resize',function(){
        width=window.innerWidth;
        if(width<995){
            document.querySelector('div.SmallLogo').style.display="flex";
            document.querySelector('div.BigLogo').style.display="none";
        }
        else{
            document.querySelector('div.SmallLogo').style.display="none";
            document.querySelector('div.BigLogo').style.display="flex";
        }
    })
    if(width<995){
        document.querySelector('div.SmallLogo').style.display="flex";
        document.querySelector('div.BigLogo').style.display="none";
    }
    else{
        document.querySelector('div.SmallLogo').style.display="none";
        document.querySelector('div.BigLogo').style.display="flex";
    }

    //for submit button
    document.querySelector('input#name').addEventListener('input',function(){
        if(document.querySelector('input#name').value!="" && document.querySelector('input#email').value!="" && document.querySelector('input#password').value!="" && document.querySelector('input#password').value==document.querySelector('input#password-confirm').value){
            document.querySelector('button#SubmitButton').style.backgroundColor="#5094e4";
            document.querySelector('span#password-confirm').style.display="none";
        }else{
            document.querySelector('button#SubmitButton').style.backgroundColor="#90b4ec";
        }
    })
    document.querySelector('input#email').addEventListener('input',function(){
        if(document.querySelector('input#name').value!="" && document.querySelector('input#email').value!="" && document.querySelector('input#password').value!="" && document.querySelector('input#password').value==document.querySelector('input#password-confirm').value){
            document.querySelector('button#SubmitButton').style.backgroundColor="#5094e4";
            document.querySelector('span#password-confirm').style.display="none";
        }else{
            document.querySelector('button#SubmitButton').style.backgroundColor="#90b4ec";
        }
    })
    document.querySelector('input#password').addEventListener('input',function(){
        if(document.querySelector('input#name').value!="" && document.querySelector('input#email').value!="" && document.querySelector('input#password').value!="" && document.querySelector('input#password').value==document.querySelector('input#password-confirm').value){
            document.querySelector('button#SubmitButton').style.backgroundColor="#5094e4";
            document.querySelector('span#password-confirm').style.display="none";
        }else{
            document.querySelector('button#SubmitButton').style.backgroundColor="#90b4ec";
        }
    })
    document.querySelector('input#password-confirm').addEventListener('input',function(){
        if(document.querySelector('input#name').value!="" && document.querySelector('input#email').value!="" && document.querySelector('input#password').value!="" && document.querySelector('input#password').value==document.querySelector('input#password-confirm').value){
            document.querySelector('button#SubmitButton').style.backgroundColor="#5094e4";
            document.querySelector('span#password-confirm').style.display="none";
        }else if(document.querySelector('input#password').value!=document.querySelector('input#password-confirm').value){
            document.querySelector('span#password-confirm').style.display="flex";
        }else{
            document.querySelector('button#SubmitButton').style.backgroundColor="#90b4ec";
        }
    })


</script>
@endsection
