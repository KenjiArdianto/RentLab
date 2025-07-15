@extends('layouts.app')

@section('content')
<div style=" position: absolute; background-color: #102c4c; width: 100vw; height: 50vh; z-index: -1;"></div>
<div class="container" style="width: 100vw; height: 100vh; display: flex;">
    <div class="card" style="margin:  auto; padding: 40px; min-width:350px;width:35vw;padding:20px; max-width: 500px; background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);  border-radius: 10px;">
        <div class="card-body" style="padding: 40px 0; display: flex; flex-direction: column;">
            <div class="SmallLogo" style=" margin: auto; width: 80px; height: 80px; ">
                <img src="{{ asset('build/assets/images/RentLab.png') }}" style="width: 90%; height: 90%; display: flex; justify-content: center; align-items: center; margin: auto; ">
            </div>
            <p style="padding:3px;text-align: center; font-size: 25px; font-family:Verdana, Geneva, Tahoma, sans-serif; margin: 0;">Verify Your Account</p>    
            <div style="display: flex; width: 80%; margin: auto;">
                <div style="flex: 10; height: 2px; background-color: black; margin: auto;"></div>
                <p style="margin:auto; flex: 1; text-align:center; font-size: 15px; font-weight: bolder;">-</p>
                <div style="flex: 10; height: 2px; background-color: black; margin: auto;"></div>
            </div>
            @if(session('success'))
                <div class="alert alert-success" style="border: 1px solid green;color:white;background: rgba(8,68,44, 0.4); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);margin: 0; width: 80%; margin: auto;">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger" style="border: 1px solid red;color:white;background: rgba(139, 0, 0, 0.4); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);margin: 0; width: 80%; margin: auto;">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('otp.verify') }}" style="display: flex;flex-direction:column; padding-top: 5%;">
                @csrf
                <div class="form-group" style="width: 80%;display: flex;margin: auto;">
                    <div class="col-md-6" style="width: 100%;display: flex;border:2px solid black; padding: 3px;border-radius:8px;">
                        {{-- <label for="otp">Enter OTP:</label> --}}
                        <input id="OTPinput" placeholder="Enter OTP" style="margin: auto; background-color: transparent; border: none;" type="text" name="otp" class="form-control" required>
                    </div>
                </div>
                <p style="display flex;padding:2% 10% ;">
                    <a href="{{ route('resend.otp') }}" class="resendOTP" style="color: gray">Wait to resend OTP </a> 
                    <span style=" padding: 2% 5%;" class="timer">60</span>
                    <span>seconds </span>
                </p>
                <div style="width: 80%;display: flex;margin:auto; padding: 3px;">
                    <button id="SubmitButton" style=" border: 1px solid #90b4ec;;background-color: #90b4ec; width: 100%; font-weight: bold; font-size: medium; padding: 1%;" type="submit" class="btn btn-primary">Verify</button>
                </div>
            </form>
        </div>
    </div>
    <div class="BigLogo" style="width: 350px; height: 350px; background-color:  #20447c; margin: auto; border-radius: 250px; display: flex; ">
        <div style="margin: auto; background-color: white; width: 80%; height: 80%; border-radius: 200px; display: flex;">
            <img src="{{ asset('build/assets/images/RentLab.png') }}" style="width: 80%; height: 80%; display: flex; justify-content: center; align-items: center; margin: auto;">
        </div>
    </div>
</div>

<script>
    time = @json($time);
    time=Math.ceil(time);
    setInterval(function(){
        if(time<=0){
            time=0;
            if(document.querySelector('a.resendOTP').innerHTML!="Resend OTP"){
                document.querySelector('a.resendOTP').style.color="blue"
                document.querySelector('a.resendOTP').innerHTML="Resend OTP"
                document.querySelector('a.resendOTP').href="{{ route('resend.otp') }}";
            }
            
        }else{
            if(document.querySelector('a.resendOTP').innerHTML!="Wait to resend OTP"){
                document.querySelector('a.resendOTP').href="#";
                document.querySelector('a.resendOTP').style.color="gray"
                document.querySelector('a.resendOTP').innerHTML="Wait to resend OTP"
            }
            
            time=time-1;
        }
        document.querySelector('span.timer').innerHTML=time;
    },1000)
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
    document.querySelector('input#OTPinput').addEventListener('input',function(){
        if(document.querySelector('input#OTPinput').value!=""){
            document.querySelector('button#SubmitButton').style.backgroundColor="#5094e4";
        }else{
            document.querySelector('button#SubmitButton').style.backgroundColor="#90b4ec";
        }
    })
</script>
@endsection
