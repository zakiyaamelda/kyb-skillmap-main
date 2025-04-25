<div class='d-flex justify-content-center align-items-center h-100 w-100'>
    <div id='login-form-wrapper' class="m-5 background-light align-items-center justify-content-center">
        <div class='w-100'>
            <img class='w-50' src="img/kyb-merah.png" alt="">
        </div>
        <div class="w-100">
            <p class='m-0'>
                Skill Map Man Power Dashboard
            </p>
        </div>
        <div class="w-100 h-100">
            <form action="actions/login.php" class='d-flex flex-column h-100'method="post">
                <div class='d-flex-row align-items-center'>
                    <div class="w-50">
                        <label class='m-0' for="username">Username:</label>
                    </div>
                    <div class="w-100">
                        <input class="login-input" placeholder='Username' type="text" name="npk" id="npk" required>
                    </div>
                </div>
                <div class='d-flex-row pt-3 align-items-center'>
                    <div class="w-50">
                        <label class='m-0' for="password">Password:</label>
                    </div>
                    <div class="w-100">
                        <input class="login-input" placeholder='Password' type="password" name="pwd" id="pwd" required>
                    </div>
                </div>
                <div class='mt-3 d-flex flex-row align-items-center justify-content-center'>                    
                    <img src="captcha.php?rand=<?php echo rand(); ?>" id='captcha_image'>
                    <input type="text" name="captcha" placeholder="Captcha" />
                </div>
                <div>
                    <p>Captcha tidak terbaca? Refresh <a href='javascript: refreshCaptcha();'>di sini</a></p>
                </div>
                <div class="w-100 flex-float-bottom mb-3">
                    <input class='cu-submit-btn w-25' type="submit" value="LOGIN">
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function refreshCaptcha(){
    var img = document.images['captcha_image'];
    img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
}
</script>