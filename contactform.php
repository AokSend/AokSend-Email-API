<?php
if(!empty($_POST['is_post']) && $_POST['is_post']==1){
    $url = "https://www.aoksend.com/index/api/send_email";
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    if(empty($name)){
        echo json_encode(['message'=>'请填写Name','code' => 40001]);
        exit;
    }
    if(empty($email)){
        echo json_encode(['message'=>'请填写Email address','code' => 40002]);
        exit;
    }
    if(empty($subject)){
        echo json_encode(['message'=>'请填写Subject','code' => 40003]);
        exit;
    }
    if(empty($message)){
        echo json_encode(['message'=>'请填写Message','code' => 40004]);
        exit;
    }
    $time = date('Y-m-d H:i:s',time());

    $str = '{"username":"'.$name.'","contactemail":"'.$email.'","subject":"'.$subject.'","content":"'.$message.'","time":"'.$time.'"}';

    //app_key 注册Aoksend获取秘钥
    //to 需要接收提醒的邮箱
    //template_id Aoksend里的邮件模板ID
    $data = ['app_key'=>'cf6d0114ee5cd1e4800000005c20ac793', 'to'=>'test@Aoksend.com', 'template_id'=>'E_100008454408', 'data'=>$str];

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)){
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    echo $output;
    exit;
}
?>
<style type="text/css">
html, body {
  background: #f1f1f1;
  font-family: 'Merriweather', sans-serif;
  padding: 1em;
}

h1 {
   text-align: center;
   color: #565656;
   @include text-shadow(1px 1px 0 rgba(white, 1));
}
p{
  text-align: center;
}
form {
   max-width: 600px;
   text-align: center;
   margin: 20px auto;
  
  input, textarea {
     border:0; outline:0;
     padding: 1em;
     @include border-radius(8px);
     display: block;
     width: 100%;
     margin-top: 1em;
     font-family: 'Merriweather', sans-serif;
     @include box-shadow(0 1px 1px rgba(black, 0.1));
     resize: none;
    
    &:focus {
       @include box-shadow(0 0px 2px rgba($red, 1)!important);
    }
  }
  
  #input-submit {
     color: white; 
     background-color: #ff5151;
     cursor: pointer;
     margin-top:20px;
    
    &:hover {
       @include box-shadow(0 1px 1px 1px rgba(#aaa, 0.6)); 
    }
  }
  
  textarea {
      height: 156px;
  }
}


.half {
  float: left;
  width: 48%;
  margin-bottom: 1em;
}

.right { width: 50%; }

.left {
     margin-right: 2%; 
}


@media (max-width: 480px) {
  .half {
     width: 100%; 
     float: none;
     margin-bottom: 0; 
  }
}


/* Clearfix */
.cf:before,
.cf:after {
    content: " "; /* 1 */
    display: table; /* 2 */
}

.cf:after {
    clear: both;
}
</style>
<h1>联系表单</h1>
<p>由AokSend支持邮件发送API</p>
<form class="cf">
  <div class="half left cf">
    <input type="text" id="input-name" placeholder="Name">
    <input type="email" id="input-email" placeholder="Email address">
    <input type="text" id="input-subject" placeholder="Subject">
  </div>
  <div class="half right cf">
    <textarea name="message" type="text" id="input-message" placeholder="Message"></textarea>
  </div>  
  <input type="submit" value="Submit" id="input-submit">
</form>

<script>
  function submitForm() {
    // 阻止表单的默认提交行为
    event.preventDefault();

    // 假设你的表单数据在以下对象中
    var formData = {
      is_post: 1,
      name: document.getElementById('input-name').value,
      email: document.getElementById('input-email').value,
      subject: document.getElementById('input-subject').value,
      message: document.getElementById('input-message').value
    };

    // 将表单数据转换为查询字符串
    var queryString = Object.keys(formData).map(key => encodeURIComponent(key) + '=' + encodeURIComponent(formData[key])).join('&');

    // 初始化XMLHttpRequest对象
    var xhr = new XMLHttpRequest();

    // 设置请求类型、URL和异步
    xhr.open('POST', '', true);

    // 设置请求头（如果需要）
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    // 设置响应处理函数
    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
        // 请求成功完成
        var obj = JSON.parse(xhr.responseText);
        if(obj.code==200){
          //调用成功
          alert("提交成功，已发送邮件！")
        }else{
          alert(obj.message)
        }
      }
    };
    // 发送请求
    xhr.send(queryString);
  }
  document.getElementById('input-submit').addEventListener('click', submitForm);
</script>