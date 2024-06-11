const m_strUpperCase = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
const m_strLowerCase = "abcdefghijklmnopqrstuvwxyz";
const m_strNumber = "0123456789";
const m_strCharacters = "!@#$%^&*?_~";
const regexMail = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
const regexAlpha = /^[A-Za-z]+$/;
const regexAlphaSpace = /^[A-Za-z ]+$/;
const regexPhone = /^[789]\d{9}$/;
const regexLandline = /\d{5}([- ]*)\d{6}/;

class InvalidFormError extends Error {
    constructor(message) {
        super(message)
        this.name = "Invalid Form"
    }
}

function passStrength(password) {
    document.querySelector("#pass1str").style.display = "block";
    document.querySelector("#password-span").style.display = "block";
    runPassword(document.querySelector("#" + password).value);
}

const loginFormValidator = function(event) {
    if (!new RegExp(/^[A-Za-z0-9_]+$/).test(document.getElementById("username").value)) {
        toast("Invalid username..!", 'danger', "Invalid Input");
        event.preventDefault();
        return false;
    }
};

const adminLoginFormValidator = function(event) {
    if (!regexMail.test(document.getElementById("emailid").value)) {
        toast("Invalid email..!", 'danger', "Invalid Input");
        event.preventDefault();
        return false;
    }
};

const issueBookFormValidator = function(event) {
    let userId = document.getElementById('userId').value;
    let bookId = document.getElementById('bookId').value;
    if (isNaN(userId) && parseInt(userId) < 0) {
        toast("Please enter the User Name..!", 'danger', "Invalid Input");
        event.preventDefault();
        return false;
    } else if (isNaN(bookId) && parseInt(bookId) < 0) {
        toast("Please enter the ISBN..!", 'danger', "Invalid Input");
        event.preventDefault();
        return false;
    } else if (document.getElementById('user-condition').value == '0') {
        toast("This user can't lend a book ..!", 'danger', "Invalid Input");
        event.preventDefault();
        return false;
    } else if (document.getElementById('book-condition').value == '0') {
        toast("This book is not available to lend ..!", 'danger', "Invalid Input");
        event.preventDefault();
        return false;
    }
}

const bookFormValidator = function(event) {
    try {
        flag = true;
        if (!editBookFormValidator(event)) {
            flag = false;
        }
        if (document.getElementById("coverPic") == null || document.getElementById("coverPic").value == '') {
            document.getElementById('coverPic').style.borderColor = "red";
            document.getElementById("coverPic-span").innerHTML = "Please select a cover picture..!";
            flag = false;
        } else {
            document.getElementById('coverPic').style.borderColor = "#ccc";
            document.getElementById("coverPic-span").innerHTML = "";
        }
        if (!flag) {
            event.preventDefault();
        }
    } catch (e) {
        toast("Please enter valid data");
        event.preventDefault();
    }
};

const configValidator = function(event) {
    event.preventDefault();
    flag = true;
    try {
        let fields = ['maxbookLend', 'maxLendDays', 'maxBookRequest', 'fineAmtPerDay'];
        for (const field of fields) {
            if (!document.getElementById(field)) {
                throw new InvalidFormError("Input field " + field + " is missing in the form..!");
            }
            document.getElementById(field).style.borderColor = "#ccc";
            document.getElementById(field + "-span").innerHTML = '';
        }
        if (!isNaN(document.getElementById("maxbookLend").value) && parseInt(document.getElementById("maxbookLend").value) < 1) {
            document.getElementById('maxbookLend').style.borderColor = "red";
            document.getElementById("maxbookLend-span").innerHTML = "Invalid maximum book lend count..!";
            flag = false;
        }
        if (!isNaN(document.getElementById("maxLendDays").value) && parseInt(document.getElementById("maxLendDays").value) < 1) {
            document.getElementById('maxLendDays').style.borderColor = "red";
            document.getElementById("maxLendDays-span").innerHTML = "Invalid maximum lend days..!";
            flag = false;
        }
        if (!isNaN(document.getElementById("maxBookRequest").value) && parseInt(document.getElementById("maxBookRequest").value) < 1) {
            document.getElementById('maxBookRequest').style.borderColor = "red";
            document.getElementById("maxBookRequest-span").innerHTML = "Invalid maximum book request count..!";
            flag = false;
        }
        if (!isNaN(document.getElementById("fineAmtPerDay").value) && parseInt(document.getElementById("fineAmtPerDay").value) < 1) {
            document.getElementById('fullname').style.borderColor = "red";
            document.getElementById("fineAmtPerDay-span").innerHTML = "Invalid fine amount..!";
            flag = false;
        }
        if (flag) {
            for (const field of fields) {
                if (!document.getElementById(field)) {
                    throw new InvalidFormError("Input field " + field + " is missing in the form..!");
                }
            }
            const formData = event.currentTarget;
            fetch('/admin/settings', { method: 'post', body: new FormData(formData), headers: { response: "application/json" } })
                .then(response => { return response.json() })
                .then(data => {
                    toast(data.message);
                });
        } else {
            toast("Please enter the valid input", "danager", "Invalid Input");
        }
    } catch (e) {
        if (e instanceof InvalidFormError) {
            toast(e.message);
        } else {
            toast("Something went wrong..!");
        }
        return false;
    }
}

const cmsValidator = function(event) {
    event.preventDefault();
    flag = true;
    try {
        let fields = ['aboutus', 'address', 'mission', 'emailid', 'mobile'];
        for (const field of fields) {
            if (!document.getElementById(field)) {
                throw new InvalidFormError("Input field " + field + " is missing in the form..!");
            }
            document.getElementById(field).style.borderColor = "#ccc";
            document.getElementById(field + "-span").innerHTML = '';
        }
        if ((document.getElementById("aboutus").value) == '') {
            document.getElementById('aboutus').style.borderColor = "red";
            document.getElementById("aboutus-span").innerHTML = "Please enter aboutus contentens..!";
            flag = false;
        }
        if ((document.getElementById("address").value) == '') {
            document.getElementById('address').style.borderColor = "red";
            document.getElementById("address-span").innerHTML = "Please enter the address..!";
            flag = false;
        }
        if ((document.getElementById("mission").value) == '') {
            document.getElementById('mission').style.borderColor = "red";
            document.getElementById("mission-span").innerHTML = "Please enter the mission contents..!";
            flag = false;
        }
        if ((document.getElementById("vision").value) == '') {
            document.getElementById('vision').style.borderColor = "red";
            document.getElementById("vision-span").innerHTML = "Please enter the vision contents..!";
            flag = false;
        }
        if (!regexMail.test(document.getElementById("emailid").value)) {
            document.getElementById('emailid').style.borderColor = "red";
            document.getElementById("emailid-span").innerHTML = "Please enter valid email id..!";
            flag = false;
        }
        if (!regexLandline.test(document.getElementById("mobile").value)) {
            document.getElementById('mobile').style.borderColor = "red";
            document.getElementById("mobile-span").innerHTML = "Please enter valid phone number..!";
            flag = false;
        }
        if (flag) {
            for (const field of fields) {
                if (!document.getElementById(field)) {
                    throw new InvalidFormError("Input field " + field + " is missing in the form..!");
                }
            }
            const formData = event.currentTarget;
            fetch('/admin/cms', { method: 'post', body: new FormData(formData), headers: { response: "application/json" } })
                .then(response => { return response.json() })
                .then(data => {
                    toast(data.message);
                });
        } else {
            toast("Please enter the valid input", "danager", "Invalid Input");
        }
    } catch (e) {
        if (e instanceof InvalidFormError) {
            toast(e.message);
        } else {
            toast("Something went wrong..!");
        }
        return false;
    }
}


const editBookFormValidator = function(event) {
    flag = true;
    try {
        let fields = ['price', 'stack', 'publication', 'description', 'isbn', 'selected-author', 'selected-category', 'bookname', 'location'];
        for (const field of fields) {
            if (!document.getElementById(field)) {
                event.preventDefault();
                throw new InvalidFormError("Input field " + field + " is missing in the form..!");
            }
            document.getElementById(field).style.borderColor = "#ccc";
            document.getElementById(field + "-span").innerHTML = '';
        }
        if (!isNaN(document.getElementById("price").value) && parseInt(document.getElementById("price").value) <= 0) {
            document.getElementById('price').style.borderColor = "red";
            document.getElementById("price-span").innerHTML = "Invalid price..!";
            flag = false;
        }
        if (!isNaN(document.getElementById("stack").value) && parseInt(document.getElementById("stack").value) <= 0) {
            document.getElementById('stack').style.borderColor = "red";
            document.getElementById("stack-span").innerHTML = "Invalid stack..!";
            flag = false;
        }
        if (!new RegExp(/^[1-9]{1}[0-9,]*$/).test(document.getElementById("selected-author").value)) {
            document.getElementById('selected-author').style.borderColor = "red";
            document.getElementById("selected-author-span").innerHTML = "Please select Authors..!";
            flag = false;
        }
        if (!new RegExp(/^[1-9]{1}[0-9,]*$/).test(document.getElementById("selected-category").value)) {
            document.getElementById('selected-category').style.borderColor = "red";
            document.getElementById("selected-category-span").innerHTML = "Please select categories..!!";
            flag = false;
        }
        if (document.getElementById("bookname").value == '') {
            document.getElementById('bookname').style.borderColor = "red";
            document.getElementById("bookname-span").innerHTML = "Please enter the book name..!";
            flag = false;
        }
        if (document.getElementById("location").value == '') {
            document.getElementById('location').style.borderColor = "red";
            document.getElementById("location-span").innerHTML = "Please enter the book location..!";
            flag = false;
        }
        if (document.getElementById("publication").value == '') {
            document.getElementById('publication').style.borderColor = "red";
            document.getElementById("publication-span").innerHTML = "Please enter the publication name..!";
            flag = false;
        }
        if (document.getElementById('submit-btn').value == "0") {
            document.getElementById('isbn').style.borderColor = "red";
            document.getElementById("isbn-span").innerHTML = "ISBN already exists";
            flag = false;
        }
        if (!isbnValidator(document.getElementById("isbn").value)) {
            document.getElementById('isbn').style.borderColor = "red";
            document.getElementById("isbn-span").innerHTML = "Invalid ISBN..!";
            flag = false;
        }
        if (document.getElementById("description").value == '') {
            document.getElementById('description').style.borderColor = "red";
            document.getElementById("description-span").innerHTML = "Please enter the book's description..!";
            flag = false;
        }
        if (document.getElementById("price").value == '') {
            document.getElementById('price').style.borderColor = "red";
            document.getElementById("price-span").innerHTML = "Please enter the book price..!";
            flag = false;
        }
        if (flag) {
            for (const field of fields) {
                if (!document.getElementById(field)) {
                    event.preventDefault();
                    throw new InvalidFormError("Input field " + field + " is missing in the form..!");
                }
            }
            return true;
        } else {
            event.preventDefault();
            toast("Please enter the valid input", "danager", "Invalid Input");
        }
    } catch (e) {
        event.preventDefault();
        if (e instanceof InvalidFormError) {
            toast(e.message);
        } else {
            toast("Something went wrong..!");
        }
        return false;
    }
};

const nameValidator = function(event) {
    form = new FormData(event.target);
    if (!regexAlphaSpace.test(form.get('name'))) {
        toast("Invalid name..!", "danger", "Invalid Input");
        event.preventDefault();
        return false;
    }
}

const passwordValidator = function(event) {
    let password = document.getElementById("password").value;
    let confirmPassword = document.getElementById("confirmPassword").value;
    if (password.length < 6) {
        toast("Password is too short.. It should be six character long..!", 'danger', "Invalid Input");
        event.preventDefault();
        return false;
    } else if (password != confirmPassword) {
        toast("Please confirm your password..!", "danger", "Invalid Input");
        event.preventDefault();
        return false;
    } else if (checkPassword(password) <= 50) {
        toast("Please select a strong Password..!", "danger", "Warning");
        event.preventDefault();
        return false;
    }
}
const usernameValidator = function(event) {
    form = new FormData(event.target);
    if (!new RegExp(/^[A-Za-z0-9_]+$/).test(form.get('username'))) {
        toast("Invalid username..!", "danger", "Invalid Input");
        event.preventDefault();
        return false;
    }
}

const emailValidator = function(event) {
    form = new FormData(event.target);
    if (!regexMail.test(form.get('email'))) {
        toast("Invalid email..!", "danger", "Invalid Input");
        event.preventDefault();
        return false;
    }
}

const registrationFormValidator = function(event) {
    event.preventDefault();
    flag = true;
    try {
        let fields = ['fullname', 'username', 'password', 'confirmPassword', 'emailid', 'mobile', 'vercode', 'gender'];
        for (const field of fields) {
            if (!document.getElementById(field)) {
                throw new InvalidFormError("Input field " + field + " is missing in the form..!");
            }
            document.getElementById(field).style.borderColor = "#ccc";
            document.getElementById(field + "-span").innerHTML = '';
        }

        let password = document.getElementById("password").value;
        let confirmPassword = document.getElementById("confirmPassword").value;
        if (!regexAlphaSpace.test(document.getElementById('fullname').value)) {
            document.getElementById('fullname').style.borderColor = "red";
            document.getElementById("fullname-span").innerHTML = "Invalid fullname";
            flag = false;
        }
        if (!new RegExp(/^[A-Za-z]{1}[A-Za-z0-9_]+$/).test(document.getElementById("username").value)) {
            document.getElementById('username').style.borderColor = "red";
            document.getElementById("username-span").innerHTML = "Invalid username";
            flag = false;
        }
        if (password.length < 6) {
            document.getElementById('password').style.borderColor = "red";
            document.getElementById("password-span").innerHTML = "Password is too short.. It should be six character long..!";
            flag = false;
        }
        if (!regexMail.test(document.getElementById("emailid").value)) {
            document.getElementById('emailid').style.borderColor = "red";
            document.getElementById("emailid-span").innerHTML = "Invalid email";
            flag = false;
        }
        if (!regexPhone.test(document.getElementById("mobile").value)) {
            document.getElementById('mobile').style.borderColor = "red";
            document.getElementById("mobile-span").innerHTML = "Invalid mobile";
            flag = false;
        }
        if (password != confirmPassword) {
            document.getElementById('confirmPassword').style.borderColor = "red";
            document.getElementById("confirmPassword-span").innerHTML = "Please confirm your password..!";
            flag = false;
        }
        if (checkPassword(password) <= 50) {
            document.getElementById('password').style.borderColor = "red";
            document.getElementById("password-span").innerHTML = "Please select a strong Password..!";
            flag = false;
        }
        if (document.getElementById('vercode').value == '') {
            document.getElementById('vercode').style.borderColor = "red";
            document.getElementById("vercode-span").innerHTML = "Please enter captcha";
            flag = false;
        }
        if (document.getElementById('gender').value == '') {
            document.getElementById('gender').style.borderColor = "red";
            document.getElementById("gender-span").innerHTML = "Please select the gender";
            flag = false;
        }
        if (document.getElementById('submit-btn').value == "0") {
            toast("User already exists");
            return false;
        }

        if (flag) {
            for (const field of fields) {
                if (!document.getElementById(field)) {
                    throw new InvalidFormError("Input field " + field + " is missing in the form..!");
                }
            }
            const formData = event.currentTarget;
            fetch('/signup', { method: 'post', body: new FormData(formData), headers: { response: "application/json" } })
                .then(response => { return response.json() })
                .then(data => {
                    if (data.result == 1) {
                        window.location.replace('login');
                    } else {
                        toast(data.message);
                    }
                });
        } else {
            toast("Please enter the valid input", "danager", "Invalid Input");
        }
    } catch (e) {
        if (e instanceof InvalidFormError) {
            toast(e.message);
        } else {
            toast("Something went wrong..!");
        }
        return false;
    }

}

// const categoryValidator = function(event) {
//     if (!regexAlphaSpace.test(document.getElementById("catname").value)) {
//         toast("Invalid Category Name..!", "danger", "Invalid Input");
//         event.preventDefault();
//         return false;
//     }
// }

const authorValidator = function(event) {
    if (!regexAlphaSpace.test(document.getElementById("autname").value)) {
        toast("Invalid Author Name..!", "danger", "Invalid Input");
        event.preventDefault();
        return false;
    }
}

const userProfileValidator = function(event) {
    event.preventDefault();
    flag = true;
    try {
        let fields = ['fullname', 'password', 'confirmPassword', 'mobile', 'gender'];
        for (const field of fields) {
            if (!document.getElementById(field)) {
                throw new InvalidFormError("Input field " + field + " is missing in the form..!");
            }
            document.getElementById(field).style.borderColor = "#ccc";
            document.getElementById(field + "-span").innerHTML = '';
        }
        let password = document.getElementById("password").value;
        let confirmPassword = document.getElementById("confirmPassword").value;
        if (!regexAlphaSpace.test(document.getElementById('fullname').value)) {
            document.getElementById('fullname').style.borderColor = "red";
            document.getElementById("fullname-span").innerHTML = "Invalid fullname";
            flag = false;
        }
        if (password.length < 6 && password != '') {
            document.getElementById('password').style.borderColor = "red";
            document.getElementById("password-span").innerHTML = "Password is too short.. It should be six character long..!";
            flag = false;
        }
        if (!regexPhone.test(document.getElementById("mobile").value)) {
            document.getElementById('mobile').style.borderColor = "red";
            document.getElementById("mobile-span").innerHTML = "Invalid mobile";
            flag = false;
        }
        if (password != confirmPassword) {
            document.getElementById('confirmPassword').style.borderColor = "red";
            document.getElementById("confirmPassword-span").innerHTML = "Please confirm your password..!";
            flag = false;
        }
        if (password != '' && checkPassword(password) <= 50) {
            document.getElementById('password').style.borderColor = "red";
            document.getElementById("password-span").innerHTML = "Please select a strong Password..!";
            flag = false;
        }
        if (document.getElementById('gender').value == '') {
            document.getElementById('gender').style.borderColor = "red";
            document.getElementById("gender-span").innerHTML = "Please select the gender";
            flag = false;
        }
        if (flag) {
            for (const field of fields) {
                if (!document.getElementById(field)) {
                    throw new InvalidFormError("Input field " + field + " is missing in the form..!");
                }
            }
            const formData = event.currentTarget;
            fetch('/user-profile', { method: 'post', body: new FormData(formData), headers: { response: "application/json" } })
                .then(response => { return response.json() })
                .then(data => {
                    toast(data.message);
                });
        } else {
            toast("Please enter the valid input", "danager", "Invalid Input");
        }
    } catch (e) {
        if (e instanceof InvalidFormError) {
            toast(e.message);
        } else {
            toast("Something went wrong..!");
        }
        return false;
    }
}

const adminProfileValidator = function(event) {
    event.preventDefault();
    flag = true;
    try {
        let fields = ['fullname', 'password', 'confirmPassword'];
        for (const field of fields) {
            if (!document.getElementById(field)) {
                throw new InvalidFormError("Input field " + field + " is missing in the form..!");
            }
            document.getElementById(field).style.borderColor = "#ccc";
            document.getElementById(field + "-span").innerHTML = '';
        }
        let password = document.getElementById("password").value;
        let confirmPassword = document.getElementById("confirmPassword").value;
        if (!regexAlphaSpace.test(document.getElementById('fullname').value)) {
            document.getElementById('fullname').style.borderColor = "red";
            document.getElementById("fullname-span").innerHTML = "Invalid fullname";
            flag = false;
        }
        if (password.length < 6 && password != '') {
            document.getElementById('password').style.borderColor = "red";
            document.getElementById("password-span").innerHTML = "Password is too short.. It should be six character long..!";
            flag = false;
        }
        if (password != confirmPassword) {
            document.getElementById('confirmPassword').style.borderColor = "red";
            document.getElementById("confirmPassword-span").innerHTML = "Please confirm your password..!";
            flag = false;
        }
        if (password != '' && checkPassword(password) <= 50) {
            document.getElementById('password').style.borderColor = "red";
            document.getElementById("password-span").innerHTML = "Please select a strong Password..!";
            flag = false;
        }
        if (flag) {
            for (const field of fields) {
                if (!document.getElementById(field)) {
                    throw new InvalidFormError("Input field " + field + " is missing in the form..!");
                }
            }
            const formData = event.currentTarget;
            fetch('/admin-profile', { method: 'post', body: new FormData(formData), headers: { response: "application/json" } })
                .then(response => { return response.json() })
                .then(data => {
                    toast(data.message);
                });
        } else {
            toast("Please enter the valid input", "danager", "Invalid Input");
        }
    } catch (e) {
        if (e instanceof InvalidFormError) {
            toast(e.message);
        } else {
            toast("Something went wrong..!");
        }
        return false;
    }
}

const createUserFormValidator = function(event) {

    event.preventDefault();
    flag = true;
    try {
        let fields = ['fullname', 'email', 'role', 'password', 'confirmPassword'];
        for (const field of fields) {
            if (!document.getElementById(field)) {
                throw new InvalidFormError("Input field " + field + " is missing in the form..!");
            }
            document.getElementById(field).style.borderColor = "#ccc";
            document.getElementById(field + "-span").innerHTML = '';
        }
        let password = document.getElementById("password").value;
        let confirmPassword = document.getElementById("confirmPassword").value;
        if (!regexAlphaSpace.test(document.getElementById('fullname').value)) {
            document.getElementById('fullname').style.borderColor = "red";
            document.getElementById("fullname-span").innerHTML = "Invalid fullname";
            flag = false;
        }
        if (password.length < 6) {
            document.getElementById('password').style.borderColor = "red";
            document.getElementById("password-span").innerHTML = "Password is too short.. It should be six character long..!";
            flag = false;
        }
        if (document.getElementById('role').value == '') {
            document.getElementById('role').style.borderColor = "red";
            document.getElementById("role-span").innerHTML = "Please select the role";
            flag = false;
        }
        if (!regexMail.test(document.getElementById("email").value)) {
            document.getElementById('email').style.borderColor = "red";
            document.getElementById("email-span").innerHTML = "Invalid email..!";
            flag = false;
        }
        if (document.getElementById('submit-btn').value == "0") {
            document.getElementById('email').style.borderColor = "red";
            document.getElementById("email-span").innerHTML = "This email id already registered";
            flag = false;
        }
        if (password != confirmPassword) {
            document.getElementById('confirmPassword').style.borderColor = "red";
            document.getElementById("confirmPassword-span").innerHTML = "Please confirm your password..!";
            flag = false;
        }
        if (checkPassword(password) <= 50) {
            document.getElementById('password').style.borderColor = "red";
            document.getElementById("password-span").innerHTML = "Please select a strong password..!";
            flag = false;
        }
        if (flag) {
            for (const field of fields) {
                if (!document.getElementById(field)) {
                    throw new InvalidFormError("Input field " + field + " is missing in the form..!");
                }
            }
            const formData = event.currentTarget;
            fetch('/admin/user-management', { method: 'post', body: new FormData(formData), headers: { response: "application/json" } })
                .then(response => { return response.json() })
                .then(data => {
                    toast(data.message);
                    if (data.result == '1') {
                        closeModal('addRecord');
                    }
                });
        } else {
            toast("Please enter the valid input", "danager", "Invalid Input");
        }
    } catch (e) {
        if (e instanceof InvalidFormError) {
            toast(e.message);
        } else {
            toast("Something went wrong..!");
        }
        return false;
    }

}

function checkUserName(username, span_id) {
    if (!new RegExp(/^[A-Za-z0-9_]+$/).test(username)) {
        document.getElementById(span_id).innerHTML = "Invalid username";
        document.getElementById("submit-btn").value = 0;
    }
    fetch("/user-management/users/user-name/" + username, { headers: { response: "application/json" } })
        .then(response => { return response.json() })
        .then(data => {
            if (data.result == false) {
                document.getElementById(span_id).innerHTML = "";
                document.getElementById("submit-btn").value = 1;
            } else {
                document.getElementById(span_id).innerHTML = "User name is not available";
                document.getElementById("submit-btn").value = 0;
            }
        });
}

function isbnAvailability(isbn, span_id, id) {
    if (!isbnValidator(isbn)) {
        document.getElementById(span_id).innerHTML = "Invalid ISBN";
        document.getElementById("submit-btn").value = 0;
    }
    fetch("/book-management/books/isbn/" + isbn + "?id=" + id, {
            headers: { response: "application/json" },
        })
        .then(response => { return response.json() })
        .then(data => {
            if (data.result == false) {
                document.getElementById(span_id).innerHTML = "";
                document.getElementById("submit-btn").value = 1;
            } else {
                document.getElementById(span_id).innerHTML = "This ISBN is already exists..!";
                document.getElementById("submit-btn").value = 0;
            }
        });
}

function checkEmail(email, span_id, isAdmin = false) {
    url = isAdmin ? "/user-management/admin-users/email/" : "/user-management/users/email/";
    if (!regexMail.test(email)) {
        document.getElementById(span_id).innerHTML = "Invalid email id";
        document.getElementById("submit-btn").value = 0;
    }
    fetch(url + email, { headers: { response: "application/json" } })
        .then(response => { return response.json() })
        .then(data => {
            if (data.result == false) {
                document.getElementById(span_id).innerHTML = "";
                document.getElementById("submit-btn").value = 1;
            } else {
                document.getElementById(span_id).innerHTML = "Email id is already registered";
                document.getElementById("submit-btn").value = 0;
            }
        });
}

const isbnValidator = function(isbn) {
    let sum = 0,
        digit;
    let n = isbn.length;
    if (n != 10) {
        return false;
    }
    for (let i = 0; i < 9; i++) {
        if (isNaN(isbn[i])) {
            return false;
        }
        digit = parseInt(isbn[i]);
        sum += (digit * (10 - i));
    }
    last = isbn[9];
    if (last != 'X' && (isNaN(last))) {
        return false;
    }
    sum += ((last == 'X') ? 10 : (parseInt(last)));
    return (sum % 11 == 0);
}

function checkPassword(strPassword) {
    // Reset combination count
    var nScore = 0;
    // Password length
    // -- length Less than 4 characters
    if (strPassword.length < 5) {
        nScore += 5;
    }
    // -- length 5 to 7 characters
    else if (strPassword.length > 4 && strPassword.length < 8) {
        nScore += 10;
    }
    // -- length 8 or more
    else if (strPassword.length > 7) {
        nScore += 25;
    }
    var nUpperCount = countContain(strPassword, m_strUpperCase);
    var nLowerCount = countContain(strPassword, m_strLowerCase);
    var nLowerUpperCount = nUpperCount + nLowerCount;
    // -- Letters are all lower case
    if (nUpperCount == 0 && nLowerCount != 0) {
        nScore += 10;
    }
    // -- Letters are upper case and lower case
    else if (nUpperCount != 0 && nLowerCount != 0) {
        nScore += 20;
    }
    // Numbers
    var nNumberCount = countContain(strPassword, m_strNumber);
    // -- 1 number
    if (nNumberCount == 1) {
        nScore += 10;
    }
    // -- 3 or more numbers
    if (nNumberCount >= 3) {
        nScore += 20;
    }
    // Characters
    var nCharacterCount = countContain(strPassword, m_strCharacters);
    // -- 1 character
    if (nCharacterCount == 1) {
        nScore += 10;
    }
    // -- More than 1 character
    if (nCharacterCount > 1) {
        nScore += 25;
    }
    // Bonus
    // -- Letters and numbers
    if (nNumberCount != 0 && nLowerUpperCount != 0) {
        nScore += 2;
    }
    // -- Letters, numbers, and characters
    if (nNumberCount != 0 && nLowerUpperCount != 0 && nCharacterCount != 0) {
        nScore += 3;
    }
    // -- Mixed case letters, numbers, and characters
    if (nNumberCount != 0 && nUpperCount != 0 && nLowerCount != 0 && nCharacterCount != 0) {
        nScore += 5;
    }
    return nScore;
}

function runPassword(str) {
    var nScore = checkPassword(str);
    var color = 'black';
    var txt = '';
    if (nScore >= 90) {
        var txt = "Very Secure";
        var color = "#0ca908";
    }
    // -- Secure
    else if (nScore >= 80) {
        var txt = "Secure";
        var color = "#7ff67c";
    }
    // -- Very Strong
    else if (nScore >= 80) {
        var txt = "Very Strong";
        var color = "#008000";
    }
    // -- Strong
    else if (nScore >= 60) {
        var txt = "Strong";
        var color = "#006000";
    }
    // -- Average
    else if (nScore >= 40) {
        var txt = "Average";
        var color = "#e3cb00";
    }
    // -- Weak
    else if (nScore >= 20) {
        var txt = "Weak";
        var color = "#Fe3d1a";
    }
    // -- Very Weak
    else {
        var txt = "Very Weak";
        var color = "#e71a1a";
    }
    if (str.length == 0) {
        document.querySelector("#pass1str").style.display = "none";
        document.querySelector("#password-span").style.display = "none";
    } else {
        document.querySelector("#pass1str").style.display = "block"
        document.querySelector("#pass1str").value = nScore;
        document.querySelector("#password-span").style.color = color;
        document.querySelector("#password-span").innerHTML = txt;
    }
}

function countContain(strPassword, strCheck) {
    var nCount = 0;
    for (i = 0; i < strPassword.length; i++) {
        if (strCheck.indexOf(strPassword.charAt(i)) > -1) {
            nCount++;
        }
    }
    return nCount;
}

function checkConfirm(ele1, ele2, target) {
    if (document.querySelector("#" + ele1).value != document.querySelector("#" + ele2).value)
        document.querySelector("#" + target).innerHTML = "Passwords mismatch...!";
    else
        document.querySelector("#" + target).innerHTML = "";
}

function changePreview(event) {
    let ele = event.target.parentElement;
    let reader = new FileReader();
    if (event.target.value != "") {
        extension = event.target.files[0].name.substring(event.target.files[0].name.lastIndexOf('.') + 1).toLowerCase();
        if (extension != "png" && extension != "jpg" && extension != "jpeg") {
            alert("Please upload a image file..");
            event.target.value = "";
        } else {
            reader.onload = function() {
                preview = document.createElement("img");
                preview.src = reader.result;
                preview.className = "file-preview";
                preview.id = "file-preview";
                oldEle = document.getElementById('file-preview');
                if (oldEle != null && ele.contains(oldEle)) {
                    oldEle.remove();
                }
                ele.appendChild(preview);
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    }
}

if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}