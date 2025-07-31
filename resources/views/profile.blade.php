<x-layout>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>User Profile UI</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      padding: 0;
      background: #ffffff;
      font-family: 'Inter', sans-serif;
      
    }
    
    .card {
      opacity: 100%;
      max-width: 480px;
      margin: 40px auto;
      background: #ffffff;
      border-radius: 16px;
      overflow: hidden;
      color: black;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
    }
    .header {
      background: #d6d6d6;
      height: 120px;
      position: relative;
    }
    .avatar {
      width: 72px;
      height: 72px;
      border-radius: 50%;
      background: #a3a3a3;
      position: absolute;
      left: 20px;
      bottom: -36px;
      border: 3px solid #ffffff;
      cursor: pointer;
      background-position: center;
      background-size: contain;
      background-repeat: no-repeat;
    }
    .avatar:hover{
      opacity:90%;
    }
    .content {
      padding: 20px;
      padding-top: 40px;
    }
    .name {
      font-size: 20px;
      font-weight: 600;
    }
    .email {
      font-size: 14px;
      color: #9ca3af;
      margin-bottom: 12px;
    }
    .status {
      display: inline-block;
      font-size: 12px;
      color: #064e3b;
      background: #10b981;
      padding: 2px 8px;
      border-radius: 9999px;
      margin-left: 6px;
    }
    .info-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 12px;
      margin: 16px 0;
      font-size: 13px;
    }
    .info-grid div {
      color: black;
    }
    .info-grid span {
      display: block;
      font-size: 12px;
      color: #5e5e5e;
    }
    .form-section {
      display: flex;
      flex-direction: column;
      gap: 16px;
      margin-top: 16px;
    }
    label {
      display: block;
      font-size: 13px;
      margin-bottom: 4px;
      color: black;
    }
    .input-group {
      display: flex;
      gap: 12px;
    }
    .input-group div input, #buttonIdCard{
        width: 88%;
    }
    input, select , #buttonIdCard{
      flex: 1;
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #374151;
      background: #f2f3f4;
      color: #5e5e5e;
      font-size: 14px;
    }

    #buttonIdCard:hover{
      border: 1px solid white;
    }
    
    .email-group {
      position: relative;
      display: flex;
    }
    .email-group input {
      padding-left: 40px;
    }
    .email-icon {
      position: absolute;
      top: 50%;
      left: 12px;
      transform: translateY(-50%);
      font-size: 14px;
      color: #9ca3af;
    }
    .verified-text {
      color: #60a5fa;
      font-size: 12px;
      margin-top: 4px;
      display: flex;
      align-items: center;
      gap: 6px;
    }
    .verified-text::before {
      content: '✔';
      color: #60a5fa;
    }
    .select-country {
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .flag {
      width: 20px;
      height: 14px;
      background: red; /* Replace with image if desired */
      border-radius: 2px;
    }
    .username-input {
      display: flex;
      align-items: center;
      gap: 4px;
    }
    .username-input span {
      font-size: 14px;
      color: black;
    }
    .btn-group {
      display: flex;
      justify-content: space-between;
      margin-top: 24px;
    }
    button {
      padding: 10px 16px;
      border-radius: 8px;
      font-size: 14px;
      border: none;
      cursor: pointer;
    }
    .cancel-btn {
      background: #eaeaea;
      color: #5e5e5e;
    }
    .save-btn {
      background: #2563eb;
      color: white;
    }
    .top-right-buttons {
      position: absolute;
      top: 16px;
      right: 16px;
      display: flex;
      gap: 8px;
    }
    .top-right-buttons button {
      background: #1f2937;
      color: #f9fafb;
      font-size: 13px;
      padding: 6px 12px;
      border-radius: 8px;
    }
    .imageOfIdCard{
      width: 100%;
      height: 0;
      background-color: #1f2937;
      margin: auto;
      transition: height 0.3s ease;
      cursor: pointer;
      background-position: center;
      background-size: contain;
      background-repeat: no-repeat;
    }
    .imageOfIdCard.active{
      height: 200px;
    }
    .imageOfIdCard:hover{
      opacity: 50%;
    }

    .bg-popup{
      position: absolute;
      width:100%;
      height: 95%;
      z-index: 3;
      display: none;
    }

    .bg-popup.active{
      display: flex;
    }

    .profile-popup{ 
      background: #111827;
      margin:auto;
      border-radius: 50px;
      display:flex;
      flex-direction: column;
    }

    .profile-image-popup{
      width: 400px;
      height: 400px;
      background-color: #1f2937;
      margin: 30px 60px;
      border-radius: 500px;
      cursor: pointer;
      background-position: center;
      background-size: contain;
      background-repeat: no-repeat;
    }

    .hiddenMenu{
      height: 500px;
      width: 500px;
      display: flex;
      flex-direction: column;
    }

    .hiddenMenu div{
      margin: auto;
      display: flex;
      flex-direction: column;
    }
    .unAuthHiddenMenu {
  background-color: #111827;
  padding: 40px 30px;
  border-radius: 30px;
  width: 90%;
  max-width: 400px;
  margin: 50px auto;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
  text-align: center;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.unAuthHiddenMenu h1 {
  color: white;
  font-size: 24px;
  margin-bottom: 100px;
}

.unAuthHiddenMenu .auth-btn {
  display: block;
  text-decoration: none;
  padding: 12px 20px;
  margin-bottom: 20px;
  border-radius: 8px;
  font-weight: 600;
  font-size: 16px;
  transition: background-color 0.3s ease;
  color: white;
}

.unAuthHiddenMenu .auth-btn.login {
  background-color: #3b82f6;
}

.unAuthHiddenMenu .auth-btn.login:hover {
  background-color: #2563eb;
}

.unAuthHiddenMenu .auth-btn.register {
  background-color: #10b981;
}

.unAuthHiddenMenu .auth-btn.register:hover {
  background-color: #059669;
}


.unUserDetailHiddenMenu {
  background-color: #111827;
  padding: 40px 30px;
  border-radius: 30px;
  width: 90%;
  max-width: 400px;
  margin: auto;
  box-shadow: 0 8px 24px rgba(0,0,0,0.3);
  text-align: center;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.unUserDetailHiddenMenu h1 {
  color: white;
  font-size: 24px;
  margin-bottom: 80px;
}

.unUserDetailHiddenMenu button {
  width: 100%;
  padding: 12px 20px;
  margin-bottom: 15px;
  font-size: 16px;
  font-weight: 600;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.unUserDetailHiddenMenu button:first-of-type {
  background-color: white;
  color: #111827;
  border: 2px solid #111827;
}

.unUserDetailHiddenMenu button:nth-of-type(2),
.unUserDetailHiddenMenu button:nth-of-type(3) {
  background-color: #dc2626;
  color: white;
  border: 2px solid #f87171;
}

    .profile-image-popup:hover{
      opacity: 60%;
    }

    .seperator-popup{
      display: flex;
      width: 100%;
      height: 30px;
    }

    .buttons-popup{
      width: 100%;
      display: flex;
      background: #1f2937;
      border-radius: 0 0 50px 50px;
    }

    .popup-button{
      flex: 1;
      height: 70px;
      color: white;
      display: flex;
      text-align: center;
      border: 3px solid #374151;
      font-size: 20px;
      font-weight: 600;
    }

    .popup-button:hover{
      opacity: 80%;
      color: #374151;
    }

  </style>
</head>

<body>
  <div class="bg-popup" onclick="closePopup()">
    
    <div class="profile-popup" onclick="event.stopPropagation()">


        <label class="profile-image-popup">
            <input type="file" id="uploadProfileImage" accept="image/*" style="display: none;" name="profilePicture">
        </label>
        <div class="seperator-popup"></div>
        <div class="buttons-popup">
          <div class="popup-button" style="border-radius: 0 0 0 50px"><p style="margin: auto;">Cancel</p></div>
          <div class="popup-button"><p style="margin: auto;">Delete</p></div>
          <div class="popup-button" style="border-radius: 0 0 50px 0"><p style="margin: auto;">Confirm</p></div>
        </div>
        

        <div class="hiddenMenu" style="display: none;">
          <div class="unAuthHiddenMenu" style="display: none;">
            <h1>Introduce Yourself</h1>

            <a href="{{ route('login') }}" class="auth-btn login">
              Login
            </a>

            <a href="{{ route('register') }}" class="auth-btn register">
              Register
            </a>
          </div>
          <div class="unUserDetailHiddenMenu" style="display: none">
            <h1 style="color: white">Let's complete what you left</h1>
            <button onclick="window.location.href='{{ route('complete.user.detail') }}'">Complete user details</button>
            <button id="btn-DeleteAcc" onclick="event.preventDefault();document.getElementById('deleteAcc-form').submit();">Delete Account</button>
            <form id="deleteAcc-form" action="{{ route('delete.profile') }}" method="POST" class="d-none">
              @csrf
            </form>
            <button id="btn-logout" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Log Out</button>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
              @csrf
            </form>
          </div>
        
        </div>

    </div>
  </div>
      
      
  

  

  <div class="card">

    <div class="header">
      <div class="avatar"></div>

      <div class="top-right-buttons">
        <button style="background-color: orange;" id="btn-resetPasswd">Reset Password</button>
        <button style="background-color: red;" id="btn-logout" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Log Out</button>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
          @csrf
        </form>
        <button style="background-color: red;" id="btn-DeleteAcc" onclick="event.preventDefault();document.getElementById('deleteAcc-form').submit();">Delete Account</button>
        <form id="deleteAcc-form" action="{{ route('delete.profile') }}" method="POST" class="d-none">
          @csrf
        </form>
      </div>
    </div>

    <div class="content">
      <div class="name">{{ Auth::user()->name??'~' }} <span class="status">Verified</span></div>
      <div class="email">{{ Auth::user()->email??'~' }}</div>

      <div class="info-grid">
        <div><span>First Name</span>{{ Auth::user()->detail->fname??'~' }}</div>
        <div><span>Last Name</span>{{ Auth::user()->detail->lname??'~' }}</div>
        <div><span>Phone Number</span>{{ Auth::user()->detail->phoneNumber??'~' }}</div>
        <div><span>Date Of Birth</span>{{ Auth::user()->detail->dateOfBirth??'~' }}</div>
      </div>

      <div class="form-section">
        <div class="input-group">
          <div style="flex:1">
            <label>First Name</label>
            <input type="text" value="{{ Auth::user()->detail->fname??'' }}" name="fname" id="fname">
            @error('fname')
              <span class="invalid-feedback" style=" margin:0; display: flex;" role="alert">
                  <strong style="font-size: 12px; color: red; font-weight: bold;">{{ $message }}</strong>
              </span>
            @enderror
          </div>
          <div style="flex:1">
            <label>Last Name</label>
            <input type="text" value="{{ Auth::user()->detail->lname??'' }}" name="lname" id="lname">
          </div>
        </div>

        <div>
          <label>Email address</label>
          <div class="email-group">
            <span class="email-icon">📧</span>
            <input type="email" value="{{ Auth::user()->email??'' }}" name="email" id="email" readonly>
          </div>
          <div class="verified-text">Verified {{ Auth::user()->detail->dateOfBirth??'' }}</div>
        </div>

        <div>
          <div class="username-input">
            <span>Username </span>
            <input type="text" value="{{ Auth::user()->name??'' }}" name="username" id="username" class="@error('name') is-invalid @enderror">
          </div>
          @error('name')
              <span class="invalid-feedback" style=" margin:0; display: flex;" role="alert">
                  <strong style="font-size: 12px; color: red; font-weight: bold;">{{ $message }}</strong>
              </span>
          @enderror
        </div>

        <div>
          <label>Phone Number</label>
          <div class="email-group">
            <span class="email-icon">📞</span>
            <input type="text" value="{{ Auth::user()->detail->phoneNumber??'' }}" name="phoneNumber" id="phoneNumber">
          </div>
          @error('phoneNumber')
            <span class="invalid-feedback" style=" margin:0; display: flex;" role="alert">
                <strong style="font-size: 12px; color: red; font-weight: bold;">{{ $message }}</strong>
            </span>
          @enderror
        </div>

        <div>
          <label>ID Card Number</label>
          <div class="email-group">
            <span class="email-icon">🪪</span>
            <input type="text" value="{{ Auth::user()->detail->idcardNumber??'' }}" name="idCardNumber" id="idCardNumber">
          </div>
          @error('idCardNumber')
            <span class="invalid-feedback" style=" margin:0; display: flex;" role="alert">
                <strong style="font-size: 12px; color: red; font-weight: bold;">{{ $message }}</strong>
            </span>
          @enderror
        </div>


        <div class="input-group">
          <div style="flex:1">
            <label>Date of Birth</label>
            <input type="date" placeholder="dd/mm/yyyy" name="dateOfBirth" id="dateOfBirth" value="{{ Auth::user()->detail->dateOfBirth??'' }}">
            @error('dateOfBirth')
              <span class="invalid-feedback" style=" margin:0; display: flex;" role="alert">
                  <strong style="font-size: 12px; color: red; font-weight: bold;">{{ $message }}</strong>
              </span>
            @enderror
          </div>
          <div style="flex:1">
            <label>ID Card Picture</label>
            {{-- <input type="file" style="border: none; cursor: pointer;"> --}}
            <button id="buttonIdCard" style="width: 100%">Show Image</button>
            @error('idcardPicture')
              <span class="invalid-feedback" style=" margin:0; display: flex;" role="alert">
                  <strong style="font-size: 12px; color: red; font-weight: bold;">{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>

        <label class="imageOfIdCard" for="uploadIdCard">
          <input type="file" id="uploadIdCard" accept="image/*" style="display: none;" name="idcardPicture">            
        </label>

      </div>

      <div class="btn-group">
        <button class="cancel-btn">Cancel</button>
        <button class="save-btn">Save changes</button>
      </div>

    </div>
  </div>
</body>

<script>
//loading image

    window.onload = function () {
      const isLoggedIn=@json(Auth::check());
      const hasDetail = @json(optional(Auth::user())->detail !== null);
      if(!isLoggedIn || !hasDetail){
        console.log(!hasDetail)
        document.querySelector('div.bg-popup').classList.add('active');
        document.querySelector('div.card').style.opacity="30%";
        document.querySelector('label.profile-image-popup').style.display="none";
        document.querySelector('div.seperator-popup').style.display="none";
        document.querySelector('div.buttons-popup').style.display="none";
        document.querySelector('div.hiddenMenu').style.display="flex";
        if(!isLoggedIn){
          document.querySelector('div.unAuthHiddenMenu').style.display="flex"
        }else{
          document.querySelector('div.unUserDetailHiddenMenu').style.display="flex";
        }
      }
      const profilePath = @json(Auth::check()?optional(Auth::user()->detail)->profilePicture:null);
      const defaultProfile = "{{ asset('storage/profile/defaultProfile.png') }}";
      const profileUrl = profilePath ? "{{ asset('storage') }}/" + profilePath : defaultProfile;

      const profileAvatar = document.querySelector('div.avatar');
      if (profileAvatar) {
          profileAvatar.style.backgroundImage = `url('${profileUrl}')`;
          profileAvatar.style.backgroundSize = 'cover';
          profileAvatar.style.backgroundPosition = 'center';
      }
      const profilePopup = document.querySelector('label.profile-image-popup');
      if (profileAvatar) {
          profileAvatar.style.backgroundImage = `url('${profileUrl}')`;
          profileAvatar.style.backgroundSize = 'cover';
          profileAvatar.style.backgroundPosition = 'center';
      }

      // ID card picture
      const idCardPath = @json(Auth::check()?optional(Auth::user()->detail)->idcardPicture:null);
      if (idCardPath) {
          const idCardUrl = "{{ asset('storage') }}/" + idCardPath;
          const idCardLabel = document.querySelector('label.imageOfIdCard');
          if (idCardLabel) {
              idCardLabel.style.backgroundImage = `url('${idCardUrl}')`;
              idCardLabel.style.backgroundSize = 'cover';
              idCardLabel.style.backgroundPosition = 'center';
          }
      }
    };






//sending POST request
function makePostElement(type,id,name){
  const input = document.createElement('input');
  const valueFrom=document.getElementById(id);
  console.log(valueFrom);
  input.type=type;
  input.name=name;
  input.value=valueFrom.value;
  return input;
}
document.querySelector('button.save-btn').addEventListener('click',function(){
  let form =document.createElement('form');
  form.method='POST';
  form.action="{{ route('change.profile') }}";
  form.enctype = 'multipart/form-data';

  const csrfInput = document.createElement('input');
  const csrf = document.querySelector('meta[name="csrf-token"]').content;
  csrfInput.type = 'hidden';
  csrfInput.name = '_token';
  csrfInput.value = csrf;
  form.appendChild(csrfInput);
  
  
  form.appendChild(makePostElement('text','fname','fname'));
  form.appendChild(makePostElement('text','lname','lname'));
  form.appendChild(makePostElement('text','username','name'));
  form.appendChild(makePostElement('text','phoneNumber','phoneNumber'));
  form.appendChild(makePostElement('text','idCardNumber','idCardNumber'));
  form.appendChild(makePostElement('date','dateOfBirth','dateOfBirth'));
  const url = "{{ asset('storage/profile/defaultProfile.png') }}";
  if(document.querySelector('div.avatar').style.backgroundImage != `url("${url}")`){
    profilePic=document.getElementById('uploadProfileImage');
    if(!profilePic.files.length){
      const nullFileInput = document.createElement('input');
      nullFileInput.type = 'hidden';
      nullFileInput.name = 'profilePicture';
      nullFileInput.value = ''; // Laravel will interpret this as null or empty
      form.appendChild(nullFileInput);
    }else{
      form.appendChild(profilePic);
    }
  }
  form.style.display="none";
  
  idPic=document.getElementById('uploadIdCard');
  form.appendChild(idPic);

  document.body.appendChild(form);
  form.submit();



});



//for profilepic
  document.querySelector('div.avatar').addEventListener('click',function(){
    document.querySelector('div.card').style.opacity="30%";
    document.querySelector('div.bg-popup').classList.add('active');
    document.querySelector('.profile-image-popup').style.backgroundImage=document.querySelector('.avatar').style.backgroundImage;
  })
  const fileprofileInput = document.getElementById('uploadProfileImage');
  const imageprofileDiv = document.querySelector('.profile-image-popup');

  fileprofileInput.addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        imageprofileDiv.style.backgroundImage = `url('${e.target.result}')`;
      };
      reader.readAsDataURL(file);
    }
  });

  let popupbuttons=document.querySelectorAll('div.popup-button');
  console.log(popupbuttons)
  for(let x=0;x<3;x++){
    popupbuttons[x].addEventListener('click',function(){
      if(x==0){
        const defaultProfile = "{{ asset('storage/profile/defaultProfile.png') }}";
        const profilePath = @json(Auth::check()?optional(Auth::user()->detail)->profilePicture:null);
        const profileUrl = profilePath ? "{{ asset('storage') }}/" + profilePath : defaultProfile;
        document.querySelector('.avatar').style.backgroundImage = `url("${profileUrl}")`;
        document.querySelector('.profile-image-popup').style.backgroundImage=document.querySelector('.avatar').style.backgroundImage;
        const oldInput = document.querySelector('input#uploadProfileImage');
        const newInput = document.createElement('input');
        newInput.type = 'file';
        newInput.id = 'uploadProfileImage';
        newInput.name = 'profilePicture';
        newInput.accept = 'image/*';
        newInput.style.display = 'none';
        oldInput.parentNode.replaceChild(newInput,oldInput);
      }else if(x==1){
        const url = "{{ asset('storage/profile/defaultProfile.png') }}";
        document.querySelector('div.avatar').style.backgroundImage=`url('${url}')`;


      }else if(x==2){
        document.querySelector('.avatar').style.backgroundImage=document.querySelector('.profile-image-popup').style.backgroundImage;
      }
      document.querySelector('div.bg-popup').classList.remove('active');
      document.querySelector('div.card').style.opacity="100%";
    })
  }

  //for idcard
  const fileInput = document.getElementById('uploadIdCard');
  const imageDiv = document.querySelector('.imageOfIdCard');

  fileInput.addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        imageDiv.style.backgroundImage = `url('${e.target.result}')`;
      };
      reader.readAsDataURL(file);
    }
  });

  function closePopup(){
    const isLoggedIn=@json(Auth::check());
    const hasDetail = @json(optional(Auth::user())->detail !== null);
    if(isLoggedIn && hasDetail){
      document.querySelector('.bg-popup').classList.remove('active');
      document.querySelector('.card').style.opacity="100%";
    }
    
  }

  //showing idcard
  document.querySelector('button#buttonIdCard').addEventListener('click',function(){
    document.querySelector('label.imageOfIdCard').classList.toggle('active');
    if(document.querySelector('label.imageOfIdCard').classList.contains('active')){
      document.querySelector('button#buttonIdCard').textContent="Hide Image";
    }else{
      document.querySelector('button#buttonIdCard').textContent="Show Image";
    }
  })

  //cancel button
  document.querySelector('button.cancel-btn').addEventListener('click',function(){
    window.location.href="{{ route('view.profile') }}";
  })

  //passeord reset button

  document.querySelector('button#btn-resetPasswd').addEventListener('click',function(){
    window.location.href="{{ route('password.request') }}";
  })

</script>

</html>
</x-layout>
