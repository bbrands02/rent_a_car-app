$pwdI = document.getElementById('passwordInput');
$pFI = document.getElementById('passwordFeedbackI');
$cfmPwdI = document.getElementById('confirmPasswordInput');
$cFFI = document.getElementById('confirmPasswordFeedbackI');
$pFP = document.getElementById('passwordFeedbackP');

$emFP = document.getElementById('emailFeedbackP');
$emFI = document.getElementById('emailFeedbackI');
$emI = document.getElementById('emailInput');

$fNII = document.getElementById('firstNameInputI');
$fNI = document.getElementById('firstNameInput');

$regBtn = document.getElementById('registerButton');

function validatePasswords() {
    setTimeout(function () {

        if ($pwdI.value && $cfmPwdI.value) {
            $pFP.style.display = 'block';
            console.log($regBtn.value);
            if ($pwdI.value === $cfmPwdI.value) {
                $regBtn.style.display = "block";
                $pFP.style.display = 'none';
                $cFFI.style.display = 'inline-block ';
                $cFFI.style.color = 'limegreen';
                $cFFI.classList.remove('fa-warning');
                $cFFI.classList.add('fa-check');
                $pFI.style.display = 'inline-block';
                $pFI.style.color = 'limegreen';
                $pFI.classList.remove('fa-warning');
                $pFI.classList.add('fa-check');

            } else {
                $regBtn.style.display = "none";
                $pFP.innerText = 'The passwords are not identical';
                $pFP.style.color = 'red';
                $cFFI.style.display = 'inline-block';
                $cFFI.style.color = 'red';
                $cFFI.classList.remove('fa-check');
                $cFFI.classList.add('fa-warning');
                $pFI.style.display = 'inline-block';
                $pFI.style.color = 'red';
                $pFI.classList.remove('fa-check');
                $pFI.classList.add('fa-warning');
            }
        } else {
            $pFP.style.display = 'none';
            $pFI.style.display = 'none';
            $cFFI.style.display = 'none';
        }
    }, 10);
}

function validateEmail() {
    setTimeout(function () {
        if ($emI.value) {
            if (/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test($emI.value)) {
                $regBtn.style.display = "block";
                $emFP.style.display = 'none';
                $emFI.style.display = 'inline-block';
                $emFI.classList.remove('fa-warning');
                $emFI.classList.add('fa-check');
                $emFI.style.color = 'limegreen';
            } else {
                $regBtn.style.display = "none";
                $emFP.style.display = 'block';
                $emFI.style.display = 'inline-block';
                $emFI.style.color = 'red';
                $emFI.classList.remove('fa-check');
                $emFI.classList.add('fa-warning');

            }
        } else {
            $emFP.style.display = 'none';
            $emFI.style.display = 'none';
        }
    }, 10);
}

function validateText(input) {
    let i = document.getElementById(input.id.replace('Input', 'FeedbackI'));

    setTimeout(function () {
        if (input.value) {
            i.style.display = 'inline-block';
            i.classList.remove('fa-warning');
            i.classList.add('fa-check');
            i.style.color = 'limegreen';

        } else {
            i.style.display = 'none';
        }
    }, 10);

}