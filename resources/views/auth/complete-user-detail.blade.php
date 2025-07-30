@extends('layouts.app')

@section('content')
<div style=" position: absolute; background-color: #102c4c; width: 100vw; height: 50vh; z-index: -1;"></div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8" style="display: flex;height:100vh;width:100vw;">
            <div class="card" style="margin: auto; min-width:350px;width:35vw;padding:20px; max-width: 500px; background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);  border-radius: 10px;">

                <div class="card-body" style="padding: 40px 0;">
                    <div class="SmallLogo" style=" margin: auto; width: 100px; height: 100px; ">
                        <img src="{{ asset('build/assets/images/RentLab.png') }}" style="width: 90%; height: 90%; display: flex; justify-content: center; align-items: center; margin: auto; ">
                    </div>
                    <form method="POST" action="{{ route('post.user.detail') }}" enctype="multipart/form-data" style="display: flex;flex-direction:column;">
                        @csrf
                        <p style=" margin:auto ;padding:3px;text-align: center; font-weight: bold; font-size: 25px; font-family:Verdana, Geneva, Tahoma, sans-serif;">Complete your details</p>
                        <div style="display: flex; width: 80%; margin: auto;">
                            <div style="flex: 10; height: 2px; background-color: black; margin: auto;"></div>
                            <p style="margin:auto; flex: 1; text-align:center; font-size: 15px; font-weight: bolder;">-</p>
                            <div style="flex: 10; height: 2px; background-color: black; margin: auto;"></div>
                        </div>

                        @error('fname')
                            <span class="invalid-feedback" style=" margin:0 13%; display: flex;" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div class="row mb-3" style="width: 80%;display: flex;margin: auto;">
                            <div class="col-md-6" style="width: 100%;display: flex;border:2px solid black; padding: 3px 5px;border-radius:8px;">
                                <div style="margin:auto; flex: 4; font-weight: bold;">First Name </div>
                                <input placeholder="First Name" style="flex:11;margin: auto; background-color: transparent; border: none;" id="fname" type="text" class="form-control @error('fname') is-invalid @enderror" name="fname" required autocomplete="first-name">
                            </div>
                        </div>

                        @error('lname')
                            <span class="invalid-feedback" style=" margin:0 13%; display: flex;" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div class="row mb-3" style="width: 80%;display: flex;margin: auto;"> 
                            <div class="col-md-6" style="width: 100%;display: flex;border:2px solid black; padding: 3px 5px;border-radius:8px;">
                                <div style="margin:auto; flex: 4; font-weight: bold;">Last Name </div>
                                <input placeholder="Last Name" style="flex:11;margin: auto; background-color: transparent; border: none;" id="lname" type="text" class="form-control @error('lname') is-invalid @enderror" name="lname" required autocomplete="last-name">
                            </div>
                        </div>

                        @error('phoneNumber')
                            <span class="invalid-feedback" style=" margin:0 13%; display: flex;" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div class="row mb-3" style="width: 80%;display: flex;margin: auto;">
                            <div class="col-md-6" style="width: 100%;display: flex;border:2px solid black; padding: 3px 5px;border-radius:8px;">
                                <div style="margin:auto; flex: 6; font-weight: bold;">Phone Number </div>
                                <input placeholder="Phone Number" style="flex:11;margin: auto; background-color: transparent; border: none;" id="phoneNumber" type="text" class="form-control @error('phoneNumber') is-invalid @enderror" name="phoneNumber" required autocomplete="Phone-Number">
                            </div>
                        </div>

                        @error('idcardNumber')
                            <span class="invalid-feedback" style=" margin:0 13%; display: flex;" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div class="row mb-3" style="width: 80%;display: flex;margin: auto;">
                            <div class="col-md-6" style="width: 100%;display: flex;border:2px solid black; padding: 3px 5px;border-radius:8px;">
                                <div style="margin:auto; flex: 6; font-weight: bold;">ID Card Number </div>
                                <input placeholder="First Name" style="flex:11;margin: auto; background-color: transparent; border: none;" id="idcardNumber" type="text" class="form-control @error('idcardNumber') is-invalid @enderror" name="idcardNumber" required autocomplete="NIK">
                            </div>
                        </div>

                        @error('dateOfBirth')
                            <span class="invalid-feedback" style=" margin:0 13%; display: flex;" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div class="row mb-3" style="width: 80%;display: flex;margin: auto;">
                            <div class="col-md-6" style="width: 100%;display: flex;border:2px solid black; padding: 3px 5px;border-radius:8px;">
                                <div style="margin:auto; flex: 5; font-weight: bold;">Date Of Birth </div>
                                <input style="flex:11;margin: auto; background-color: transparent; border: none;" id="dateOfBirth" type="date" class="form-control @error('dateOfBirth') is-invalid @enderror" name="dateOfBirth" required autocomplete="Date-Of-Birth">
                            </div>
                            
                        </div>
                        
                        @error('idcardPicture')
                            <span class="invalid-feedback" style=" margin:0 13%; display: flex;" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div class="row mb-3" style="width: 80%;display: flex;margin: auto;">
                            <div class="col-md-6" style="width: 100%;display: flex;border:2px solid black; padding: 3px 5px;border-radius:8px;">
                                <div style="margin:auto; flex: 6; font-weight: bold;">ID Card Picture</div>
                                <input style="flex:11;margin: auto; background-color: transparent; border: none;" id="idcardPicture" type="file" accept="image/*" class="form-control @error('idcardPicture') is-invalid @enderror" name="idcardPicture" required autocomplete="NIK image">
                            </div>
                        </div>

                        <div style="width: 80%;display: flex;margin:auto; padding: 3px;">
                            <button id="SubmitButton" type="submit" class="btn btn-primary" style="width: 100%; font-weight: bold; font-size: large;background-color: #90b4ec; border: 1px solid #90b4ec;">
                                {{ __('Complete users details') }}
                            </button>
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
    document.querySelector('input#fname').addEventListener('input',function(){
        check();
    })
    document.querySelector('input#lname').addEventListener('input',function(){
        check();
    })
    document.querySelector('input#phoneNumber').addEventListener('input',function(){
        check();
    })
    document.querySelector('input#idcardNumber').addEventListener('input',function(){
        check();
    })
    document.querySelector('input#dateOfBirth').addEventListener('input',function(){
        check();
    })
    document.querySelector('input#idcardPicture').addEventListener('change',function(){
        check();
    })
    function check(){
        if(document.querySelector('input#fname').value!="" && document.querySelector('input#lname').value!="" && document.querySelector('input#phoneNumber').value!="" && document.querySelector('input#idcardNumber').value!="" && document.querySelector('input#dateOfBirth').value!="" && document.querySelector('input#idcardPicture').files.length>0){
            document.querySelector('button#SubmitButton').style.backgroundColor="#5094e4"
        }else{
            document.querySelector('button#SubmitButton').style.backgroundColor="#90b4ec"
        }
    }

    //errorhappens
    // document.querySelector('input#dateOfBirth').addEventListener("change",function(){
    //     const input=this.value;
    //     if(input){
    //         const date=new Date(input);
    //         if(isNaN(date.getTime())==false){
    //             document.querySelector('input#dateOfBirth').classList.remove("is-invalid");
    //         }
    //     }
    // })
    // document.querySelector('button#SubmitButton').addEventListener('click',function(){
    //     const input=this.value;
    //     console.log(input)
    //     if(input){
    //         const date=new Date(input);
    //     }
    //     if(!input || !date && isNaN(date.getTime())){
    //         document.querySelector('input#dateOfBirth').classList.add("is-invalid");
    //         this.type="none";
    //     }
    // })
</script>
@endsection
