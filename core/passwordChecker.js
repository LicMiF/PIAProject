
let showPasswordBtn = document.querySelector('.show-password');
let passwordInp = document.querySelector('.password-input');

showPasswordBtn.addEventListener('click', () => {

    // toggle icon 

    // font awesome class for eye icon
    showPasswordBtn.classList.toggle('fa-eye'); 

    // font awesome class for slashed eye icon
    showPasswordBtn.classList.toggle('fa-eye-slash');

    // ternary operator a shorthand for if and else to change the type of password input
    passwordInp.type = passwordInp.type === 'password' ? 'text' : 'password';

})

let passwordChecklist = document.querySelectorAll('.list-item');

let validationRegex = [
    { regex: /.{8,}/ }, // min 8 letters,
    { regex: /[0-9]/ }, // numbers from 0 - 9
    { regex: /[a-z]/ }, // letters from a - z (lowercase)
    { regex: /[A-Z]/}, // letters from A-Z (uppercase),
    { regex: /[^A-Za-z0-9]/} // special characters
]

passwordInp.addEventListener('keyup', () => {
    validationRegex.forEach((item, i) => {

        let isValid = item.regex.test(passwordInp.value);

        if(isValid) {
            passwordChecklist[i].classList.add('checked');
        } else{
            passwordChecklist[i].classList.remove('checked');
        }

    })
})